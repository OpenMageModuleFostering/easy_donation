<?php
class Wli_Donation_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
            $this->loadLayout();
            $this->renderLayout();
    }
    public function donationPostAction()
    {
        
        $donationamount = $this->getRequest()->getParam('donation_amount');
        
        $Quotes = Mage::getModel('sales/quote');
        
        Mage::getSingleton('core/session')->setDonationAmount($donationamount);
        
        $this->_redirect('checkout/cart/');
            
        
    }
}