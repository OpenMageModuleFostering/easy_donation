<?php
 
class Wli_Donation_Block_Adminhtml_Donation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('donationGrid');
        // This is the primary key of the database
        $this->setDefaultSort('donation_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('donation/donation')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('donation_id', array(
            'header'    => Mage::helper('donation')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'donation_id',
        ));
 
        $this->addColumn('order_id', array(
            'header'    => Mage::helper('donation')->__('Order ID'),
            'align'     =>'left',
            'index'     => 'order_id',
            'filter'     => false,
	    'sortable'  => false,
            'renderer'  => 'donation/adminhtml_donation_renderer_order'

        ));
 
        /*
        $this->addColumn('content', array(
            'header'    => Mage::helper('<module>')->__('Item Content'),
            'width'     => '150px',
            'index'     => 'content',
        ));
        */
 
        $this->addColumn('donation_amount', array(
            'header'    => Mage::helper('donation')->__('Donation Amount'),
            'align'     => 'left',
            'index'     => 'donation_amount',
        ));
 
        $this->addColumn('customer_firstname', array(
            'header'    => Mage::helper('donation')->__('Customer FirstName'),
            'align'     => 'left',
            'index'     => 'customer_firstname',
        ));   
 
 
        $this->addColumn('customer_lastname', array(
            'header'    => Mage::helper('donation')->__('Customer LastName'),
            'align'     => 'left',
            'index'     => 'customer_lastname',
        ));
        
        $this->addColumn('customer_email', array(
            'header'    => Mage::helper('donation')->__('Customer Email'),
            'align'     => 'left',
            'index'     => 'customer_email',
        ));
 
        return parent::_prepareColumns();
    }
 
    
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}
