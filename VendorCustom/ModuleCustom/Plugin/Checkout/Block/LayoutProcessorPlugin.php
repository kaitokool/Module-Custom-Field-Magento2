<?php

namespace VendorCustom\ModuleCustom\Plugin\Checkout\Block;

class LayoutProcessorPlugin
{
     /**
      * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
      * @param array $jsLayout
      * @return array
      */
     public function afterProcess(
          \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
          array  $jsLayout
     ) {

          $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['personal_tax_id'] = [
               'component' => 'Magento_Ui/js/form/element/abstract',
               'config' => [
                    'customScope' => 'shippingAddress.custom_attributes',
                    'customEntry' => null,
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input',
                    'id' => 'personal-tax-id'
               ],
               'dataScope' => 'shippingAddress.custom_attributes.personal_tax_id',
               'label' => 'Personal Tax Id',
               'provider' => 'checkoutProvider',
               'visible' => true,
               'sortOrder' => 250,
               'id' => 'personal-tax-id'
          ];

          $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['reference_number'] = [
               'component' => 'Magento_Ui/js/form/element/abstract',
               'config' => [
                    'customScope' => 'shippingAddress.custom_attributes',
                    'customEntry' => null,
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input',
                    'id' => 'reference-number'
               ],
               'dataScope' => 'shippingAddress.custom_attributes.reference_number',
               'label' => 'Reference Number',
               'provider' => 'checkoutProvider',
               'visible' => true,
               'sortOrder' => 251,
               'id' => 'reference-number'
          ];

          return $jsLayout;
     }
}
