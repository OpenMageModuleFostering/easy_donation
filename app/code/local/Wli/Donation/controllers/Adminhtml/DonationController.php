<?php
 
class Wli_Donation_Adminhtml_DonationController extends Mage_Adminhtml_Controller_Action
{
 
    protected function _initAction()
    {
        
        $this->loadLayout()
            ->_setActiveMenu('donation/wli_donation')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Donation Details'));
        return $this;
    }   
   
    public function indexAction() {
        $this->_initAction();       
        //$this->_addContent($this->getLayout()->createBlock('donation/adminhtml_donation'));
        $this->renderLayout();
    }

    
    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('donation/adminhtml_donation_grid')->toHtml()
        );
    }
}