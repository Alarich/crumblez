<?php

/**
 * 
 * @author Alarich
 *        
 *        
 */

class Crumblez_Model_Alliance extends Reusable_Model_Object_MyPresistable_Abstract 
{
	public $allianceId;
	public $name;
	public $towns;
	public $members;
	public $time;//update time in UNIX
	public $points;
	public $pointsBattle;
	public $pointsAttack;	
	public $pointsDefense;
	public $pointsRank;
	public $pointsBattleRank;
	public $pointsAttackRank;	
	public $pointsDefenseRank;
	
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
	
    public static function getObjectId($playerId){
    	$db = Reusable_Db_Registry::getDb();
    	$select = $db->select()->from('alliance','id')->where('alliance_id = ?',$playerId);
    	$result = $db->fetchOne($select);
    	if($result)return $result;
    	else return false;
    }
    
    
    public static function getLatestAllianceData($playerId){
    	$db = Reusable_Db_Registry::getDb();
    	$select = $db->select()->from('alliance','*')->where('alliance_id = ?',$playerId);
    	$select->order('time DESC');
    	$select->limit(1);
    	$result = $db->fetchOne($select);
    	if($result)return new self($result);
    	else return false;
    }
}

?>