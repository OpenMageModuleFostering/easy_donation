<?php
/**
 * Donation List admin grid container
 *
 * @author Weblineindia
*/
class Wli_Donation_Block_Adminhtml_Donation extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_donation';
        $this->_blockGroup = 'donation';
        $this->_headerText = Mage::helper('donation')->__('Donation Details');
				// $this->_removeButton = Mage::helper('donation')->__('Add Item');
        parent::__construct();
	$this->removeButton('add');
    }
}
