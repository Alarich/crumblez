<?php
/**
 * History klass
 * @author Alar
 *
 */
class Crumblez_Model_History extends Reusable_Model_Object_MyPresistable_Abstract
{
	public $type;
	public $datetime;
	public $info = null;
	
    
	public function __construct($conf)
	{
	    parent::__construct($conf);
	}
	
	public function mapParamsToMyArray()
	{
	    $data = parent::mapParamsToMyArray();
	    return $data;
	}
	
   
    public static function get($id) 
    {
        if (!$id)return false;
    	$result = array();
        $db = Reusable_Db_Registry::getDb();
        $fields = array('*');
        $select = $db->select()->from('history', $fields)
            ->where("id = ?",$id);
	    $row = $db->fetchRow($select);
	    return ($row)?new self($row):false;
    }
    
    public static function getWithType($type)
    {
    	if (!$type) return false;
    	$result = array();
        $db = Reusable_Db_Registry::getDb();
        $fields = array('*');
        $select = $db->select()->from('history', $fields)
            ->where("type = ?",$type);
    	$rows = $db->fetchAll($select);
	    foreach ($rows as $row){
	        $result[] = new self($row);
	    }
	    return $result;
    }
    
	public static function getAll($page = null, $perPage = null, $order = null, $direction = null, $filterParams = null){
        $result = array();
        $db = Reusable_Db_Registry::getDb();
		$fields = array(
            '*'
        );
        $select = $db->select()->from('history', $fields);
        if($page !== null && $perPage !== null) $select = $select->limitPage($page, $perPage);
        if($order !== null && $direction !== null) $select = $select->order(self::fromCamelCase($order).' '.$direction);
        if(isset($filterParams['datetime'])) $select = $select->where("datetime = ?",$filterParams['datetime']);
        if(isset($filterParams['type'])) $select = $select->where("type = ?",$filterParams['type']);
        if(isset($filterParams['info'])) $select = $select->where("lower(info) LIKE '%".strtolower($filterParams['info'])."%'");
        $rows = $db->fetchAll($select);
	    foreach ($rows as $row){
	        $result[] = new self($row);
	    }
	    return $result;
    }

    public static function getCount($filterParams)
    {        
        $db = Reusable_Db_Registry::getDb();
		$select =  $db->select()->from('history','count(*)');
        if(isset($filterParams['datetime'])) $select = $select->where("datetime = ?",$filterParams['datetime']);
        if(isset($filterParams['type'])) $select = $select->where("type = ?",$filterParams['type']);
        if(isset($filterParams['info'])) $select = $select->where("lower(info) LIKE '%".strtolower($filterParams['info'])."%'");
        return  $db->fetchOne($select);
    }
    
    public static function saveEvent($type, $info)
    {
    	$event = new self(array());
    	$event->type = $type;
    	$event->info = $info;
    	$event->save();
    }
    
}
