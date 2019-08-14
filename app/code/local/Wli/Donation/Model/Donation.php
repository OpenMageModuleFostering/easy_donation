<?php
 
class Wli_Donation_Model_Donation extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('donation/donation');
    }
    /**
	 * @param array $fieldArray, string $tableName.
	 * @param $fieldArray key === table column
	 * insert data into $tableName.
	 */
	public function insert($fieldArray,$tableName)
	{
		$query_append = null;
		$query_append .= "INSERT INTO `".Mage::getConfig()->getTablePrefix()."".$tableName."` ";
		foreach($fieldArray as $key => $val)
		{
				$query_append_field .= "`$key`".',';
				$query_append_values .= "'$val'".',';
		}
		$query_append .= '('.rtrim($query_append_field,",").') VALUES ('.rtrim($query_append_values,",").')';
	
		$this->executeSql($query_append);
	}
        /**
	* @param string $query
	* excute query in database table
	*/
	public function executeSql($query)
	{
		// fetch write database connection that is used in Mage_Core module
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');

		// now $write is an instance of Zend_Db_Adapter_Abstract
		$write->query("$query");
	}
}