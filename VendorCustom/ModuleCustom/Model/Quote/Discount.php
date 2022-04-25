<?php

namespace VendorCustom\ModuleCustom\Model\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Salesrule\Model\Validator;
use Magento\Framework\Pricing\PriceCurrencyInterface;

use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Checkout\Api\Data\ShippingInformationInterface;

class Discount extends AbstractTotal
{

     public function __construct(
          ManagerInterface $eventManager,
          StoreManagerInterface $storeManager,
          Validator $validator,
          PriceCurrencyInterface $priceCurrency
     ) {
          $this->setCode('customDiscount');
          $this->eventManager = $eventManager;
          $this->calculator = $validator;
          $this->storeManager = $storeManager;
          $this->priceCurrency = $priceCurrency;
     }

     public function collect(
          Quote $quote,
          ShippingAssignmentInterface $shippingAssignment,
          Total $total
     ) {
          parent::collect($quote, $shippingAssignment, $total);
          if (strcmp($quote->getReferenceNumber(),'00000') == 0) {
               $address = $shippingAssignment->getShipping()->getAddress();
               $label = '10%';
               $TotalAmount = $total->getSubtotal();
               $TotalAmount = $TotalAmount / 10; //Set 10% discount

               $discountAmount = "-" . $TotalAmount;
               $appliedCartDiscount = 0;

               if ($total->getDiscountDescription()) {
                    $appliedCartDiscount = $total->getDiscountAmount();
                    $discountAmount = $total->getDiscountAmount() + $discountAmount;
                    $label = $total->getDiscountDescription() . ', ' . $label;
               }

               $total->setDiscountDescription($label);
               $total->setDiscountAmount($discountAmount);
               $total->setBaseDiscountAmount($discountAmount);
               $total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
               $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);

               if (isset($appliedCartDiscount)) {
                    $total->addTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
                    $total->addBaseTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
               } else {
                    $total->addTotalAmount($this->getCode(), $discountAmount);
                    $total->addBaseTotalAmount($this->getCode(), $discountAmount);
               }
          } else {

               $total->setDiscountDescription(0);
               $total->setDiscountAmount(0);
               $total->setBaseDiscountAmount(0);
               $total->setSubtotalWithDiscount($total->getSubtotal());
               $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal());

               if (isset($appliedCartDiscount)) {
                    $total->addTotalAmount($this->getCode(), $appliedCartDiscount);
                    $total->addBaseTotalAmount($this->getCode(), $appliedCartDiscount);
               } else {
                    $total->addTotalAmount($this->getCode(), 0);
                    $total->addBaseTotalAmount($this->getCode(), 0);
               }
          }
          return $this;
     }

     public function fetch(
          Quote $quote,
          Total $total
     ) {
          $result = null;
          $amount = $total->getDiscountAmount();

          if ($amount != 0) {
               $description = $total->getDiscountDescription();
               $result = [
                    'code' => $this->getCode(),
                    'title' => strlen($description) ? __('Discount (%1)', $description) : __('Discount'),
                    'value' => $amount
               ];
          }

          return $result;
     }
}
