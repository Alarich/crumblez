<?php

/**
 * 
 * @author Alarich
 *        
 *        
 */

class Crumblez_Model_Town extends Reusable_Model_Object_MyPresistable_Abstract 
{
	public $townId;
	public $timeFounded;
	public $playerId;
	public $name;
	public $points;	
	public $islandX;	
	public $islandY;
	public $numberOnIsland;
	
	
	public static function get($conf)
	{
		//array id järgi
		if (is_int($conf) || strval(intval($conf)) === $conf){
			$db = Reusable_Db_Registry::getDb();
			$conf = $db->fetchRow($db->select()->from('town')->where('id = ?',$conf));
		}
		if(!is_array($conf)) throw new Exception('No array provided for '.__CLASS__.' '.__METHOD__);
		return new self($conf);
	}
	
	
	public static function getObjectId($playerId){
		$db = Reusable_Db_Registry::getDb();
		$select = $db->select()->from('town','id')->where('town_id = ?',$playerId);
		$result = $db->fetchOne($select);
		if($result)return $result;
		else return false;
	}
}

?>