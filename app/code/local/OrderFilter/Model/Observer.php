<?php

class GuilhermeRossato_OrderFilter_Model_Observer
{
    /*
    * This functions parses an order and cancels it if anything doesn't match a hardcoded criteria.
    *
    * To cancel a order request, use raiseError($message) method:
    *     throw Mage::throwException("Order blocked due to address not being whitelisted");
    */
    public function filterOrder($event)
    {
    }

    private function raiseError($errMessage)
    {
        Mage::getSingleton('core/session')->addNotice($errMessage);
        throw Mage::throwException("Order blocked due to address not being whitelisted");
    }

    /*
    * For when an order has to be modified / something has to be done
    */
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

    /*
    * The following functions happens before the "filterObject" method. Both methods happen before the order is placed.
    * Depending on how the payment is done, this might land you in a different page entirely
    */
    public function checkBeforeSaving(Varien_Event_Observer $observer) {
        $event = $observer->getEvent();
        $order = $event->getOrder();
        $configForTest = Mage::getStoreConfig('guilhermerossato/guilhermerossato_group/guilhermerossato_input',Mage::app()->getStore());
        $this->raiseError("Blocked before everything - before");
        return $this;
    }
    public function checkAfterSaving(Varien_Event_Observer $observer) {
        $event = $observer->getEvent();
        $order = $event->getOrder();
        $this->raiseError("Blocked before everything - after");
        return $this;
    }
}