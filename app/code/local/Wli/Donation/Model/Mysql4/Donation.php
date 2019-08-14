<?php
 
class Wli_Donation_Model_Mysql4_Donation extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('donation/donation', 'donation_id');
    }
}