<?php

namespace VendorCustom\ModuleCustom\Observer;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class AddHtmlToOrderShippingViewObserver implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->objectManager = $objectManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        if ($observer->getElementName() == 'order_shipping_view') {
            $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
            $order = $orderShippingViewBlock->getOrder();

            $customBlock = $this->objectManager->create('Magento\Framework\View\Element\Template');

            $customBlock->setPersonalTaxId($order->getPersonalTaxId());
            $customBlock->setReferenceNumber($order->getReferenceNumber());
            $customBlock->setTemplate('VendorCustom_ModuleCustom::order_info_shipping_info.phtml');

            $html = $observer->getTransport()->getOutput() . $customBlock->toHtml();

            $observer->getTransport()->setOutput($html);
        }
    }
}
