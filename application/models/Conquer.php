<?php

/**
 * TODO: history for each player and each alliance.
 * @author Alarich
 *        
 *        
 */

class Crumblez_Model_Conquer extends Reusable_Model_Object_MyPresistable_Abstract 
{
	public $townId;
	public $townPoints;
	public $time;
	public $newPlayerId;	
	public $oldPlayerId;	
	public $newAllianceId;
	public $oldAllianceId;
	
	
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
	
	public static function clearTable()
	{
		$db = Reusable_Db_Registry::getDb();
		$db->delete('conquer','1 = 1');
	}
	
}

?>