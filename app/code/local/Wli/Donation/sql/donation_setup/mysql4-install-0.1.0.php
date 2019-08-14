<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
DROP TABLE IF EXISTS {$this->getTable('donation')};
CREATE TABLE {$this->getTable('donation')} (
  `donation_id` int(11) unsigned NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `donation_amount` varchar(25) NOT NULL,
  `customer_firstname` varchar(50) NOT NULL,
  `customer_lastname` varchar(50) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  PRIMARY KEY (`donation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$staticBlock = array(
                'title' => 'Donation Help',
                'identifier' => 'donation_help',                   
                'content' => 'Demo Text for easy donate tool tip, Please check the cms static block {donation_help} to change the content.',
                'is_active' => 1,                   
                'stores' => array(0)
                );
Mage::getModel('cms/block')->setData($staticBlock)->save();


$installer->endSetup();

