<?php

class GuilhermeRossato_OrderFilter_Model_Observer
{
    public function filterOrder($event)
    {
    }

    private function raiseError($errMessage)
    {
        Mage::getSingleton('core/session')->addNotice($errMessage);
        throw Mage::throwException("Order blocked due to address not being whitelisted");
    }
    private function _processOrderStatus($order)
    {
        $invoice = $order->prepareInvoice();
        $invoice->register();
        Mage::getModel('core/resource_transaction')
           ->addObject($invoice)
           ->addObject($invoice->getOrder())
           ->save();

        $invoice->sendEmail(true, '');
        return true;
    }
    public function checkBeforeSaving(Varien_Event_Observer $observer) {
        return $this;
    }
    public function checkAfterSaving(Varien_Event_Observer $observer) {
        return $this;
    }
}