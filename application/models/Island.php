<?php

/**
 * These never change.
 * @author Alarich
 *        
 *        
 */

class Crumblez_Model_Island extends Reusable_Model_Object_MyPresistable_Abstract 
{
	public $islandId;
	public $x;
	public $y;
	public $islandType;	
	public $availableTowns;	
	
	public static function get($conf)
	{
		//array id järgi
		if (is_int($conf) || strval(intval($conf)) === $conf){
			$db = Reusable_Db_Registry::getDb();
			$conf = $db->fetchRow($db->select()->from($this->getTableName())->where('id = ?',$conf));
		}
		if(!is_array($conf)) throw new Exception('No array provided for '.__CLASS__.' '.__METHOD__);
		return new self($conf);
	}
	
	/**
	 * Returns count of islands, if 0 it is used to enable update.
	 */
	public static function chkStatus()
	{
		$db = Reusable_Db_Registry::getDb();
		$select = $db->select()->from('island','count(id)');
		return $db->fetchOne($select);
	}
	
}

?>