<?php

namespace VendorCustom\ModuleCustom\Observer;

class SaveCustomFieldsInOrder implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $order->setData('personal_tax_id', $quote->getPersonalTaxId());
        $order->setData('reference_number', $quote->getReferenceNumber());

        return $this;
    }
}
