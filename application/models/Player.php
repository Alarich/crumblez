<?php

/**
 * TODO: history for each player and each alliance.
 * @author Alarich
 *        
 *        
 */

class Crumblez_Model_Player extends Reusable_Model_Object_MyPresistable_Abstract 
{
	public $playerId;
	public $name;
	public $allianceId;
	public $points;	
	public $rank;	
	public $towns;
	
	public static function get($conf)
	{
		//array id järgi
		if (is_int($conf) || strval(intval($conf)) === $conf){
			$db = Reusable_Db_Registry::getDb();
			$conf = $db->fetchRow($db->select()->from('player')->where('id = ?',$conf));
		}
		
		if(!is_array($conf)) throw new Exception('No array provided for '.__CLASS__.' '.__METHOD__);
		return new self($conf);
	}
	
 
    public static function getAll($page = null, $perPage = null, $order = null, $direction = null, $filterParams = null){
        $result = array();
        $db = Reusable_Db_Registry::getDb();
		$fields = array(
            '*'
        );
        $select = $db->select()->from('player', $fields);
        if($page !== null && $perPage !== null) $select = $select->limitPage($page, $perPage);
        if($order !== null && $direction !== null) $select = $select->order(self::fromCamelCase($order).' '.$direction);
        
        if(isset($filterParams['orderNr'])) $select = $select->where("order_nr LIKE ?","%".$filterParams['orderNr']."%");
        
        $rows = $db->fetchAll($select);
        
	    foreach ($rows as $row){
	    	var_dump($row);
	        $result[] = new self($row);
	    }
	    return $result;
    }
    
    public static function getObjectId($playerId){
    	$db = Reusable_Db_Registry::getDb();
    	$select = $db->select()->from('player','id')->where('player_id = ?',$playerId);
    	$result = $db->fetchOne($select);
    	if($result)return $result;
    	else return false;
    }
}

?>