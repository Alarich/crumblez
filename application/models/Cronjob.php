<?php
/**
 * Cronjob klass
 * @author Alar
 *
 */
class Crumblez_Model_Cronjob extends Reusable_Model_Object_MyPresistable_Abstract
{
	public $url;
	public $completed = false;
	public $timeCalled;
	public $timeCompleted;
	public $completionAllowedStart;
	
    
	public function __construct($conf)
	{
	    parent::__construct($conf);
	}
	
   
    public static function get($id) 
    {
        if (!$id)return false;
    	$result = array();
        $db = Reusable_Db_Registry::getDb();
        $fields = array('*');
        $select = $db->select()->from('cronjob', $fields)
            ->where("id = ?",$id);
	    $row = $db->fetchRow($select);
	    return ($row)?new self($row):false;
    }
    
	public static function getAll($page = null, $perPage = null, $order = null, $direction = null, $filterParams = null){
        $result = array();
        $db = Reusable_Db_Registry::getDb();
		$fields = array(
            '*'
        );
        $select = $db->select()->from('cronjob', $fields);
        if($page !== null && $perPage !== null) $select = $select->limitPage($page, $perPage);
        if($order !== null && $direction !== null) $select = $select->order(self::fromCamelCase($order).' '.$direction);
        $rows = $db->fetchAll($select);
	    foreach ($rows as $row){
	        $result[] = new self($row);
	    }
	    return $result;
    }

    public static function getCount($filterParams)
    {        
        $db = Reusable_Db_Registry::getDb();
		$select =  $db->select()->from('cronjob','count(*)');
        return  $db->fetchOne($select);
    }
    
    
    /*
     * $commands = array ( key => val ) ex ( id = 100 ); Set behaviour in automaticCronAction CronController.
     */
    public static function saveCron($action, $commands = array(), $completionAllowedStart = null)
    {
    	if($action){
    		$cron = new self(array());
	    	$cron->url = 'http://'.$_SERVER['HTTP_HOST'].'/cron/automatic-cron/';
	    	$cron->url .= 'act/'.$action.'/';
	    	foreach($commands as $key => $val){
	    		$cron->url .= $key.'/'.$val.'/';
	    	}
	    	$cron->completionAllowedStart = $completionAllowedStart?date('Y-m-d H:i:s',strtotime($completionAllowedStart)):null;
	    	$cron->save();
    	}else{
    		$output = '';
    		foreach($commands as $key => $val){
    			$output .= $key." ".$val.PHP_EOL;
    		}
    		Crumblez_Model_History::saveEvent('Cron','cronjob adding failed:'.$output);
    	}
    	
    }
    
    public static function getOneIncomplete()
    {
    	$db = Reusable_Db_Registry::getDb();
		$conf =  $db->fetchOne($db->select()->from('cronjob','id')->where('completed = 0 AND (completion_allowed_start IS NULL OR completion_allowed_start < NOW())'));
        if(!$conf)return false;
        else return  new self($conf);
    }
    
    public static function setComplete($id)
    {
    	$cron = new self($id);
		$cron->timeCompleted = date('Y-m-d H:i:s');
		$cron->completed = true;
		$cron->save();
    }
    
}
