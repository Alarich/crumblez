<?php
abstract class Reusable_Model_Object_MyPresistable_Abstract extends Reusable_Model_Object_Abstract
{
    protected $id;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function chkIsPresisted()
    {
        return $this->id !== null;
    }
    /**
     * Accepts id to get from MySQL or array of params to map
     * @param int | array $conf
     */
    public function __construct($conf){
        //array id järgi
	    if (is_int($conf) || strval(intval($conf)) === $conf){
		   $db = Reusable_Db_Registry::getDb();
		   $conf = $db->fetchRow($db->select()->from($this->getTableName())->where('id = ?',$conf));
		}
		
		//DENY: peaks olema nüüd juba array
        if(!is_array($conf)) throw new Exception('No array provided for '.__CLASS__.' '.__METHOD__);
        
        $newConf = array();
        foreach ($conf as $name => $value){
            $newConf[$this->toCamelCase($name)] = $value;   
        }
        
        parent::__construct($newConf);
    }

    public function validate()
    {
        $badFields = array();
		$valid = true;
		$errors = array();
		
		return array($valid,$badFields,$errors);
    }
    
    public function save()
    {
        //get name
        $name = $this->getTableName();
        
        //try to find table
        $table = Reusable_Db_Registry::getTableInstance($name);
        
        //get params
        $params = $this->mapParamsToMyArray();
        
        //DENY: params not array
        if(!is_array($params)) throw new Zend_Exception('mapParamsToMyArray didnt return valid array');
        
        if(!$this->chkIsPresisted()){
            //create new
		    $this->id = $table->insert($params);
		}
		else {
		    //update existing
		    $where = $table->getAdapter()->quoteInto('id = ?', $this->getId());
			$nrOfUpdated = $table->update($params, $where);
			
			/*
			 * Kui updatetud ridade arv pole 1, siis võib olla midagi valesti
			 * a) Rida vastava idga eksisteerib, kuid update jooksutati samade andmetega - sel juhul on kõik ok
			 * b) Rida vastava idga ei eksisteeri või eksisteerib mitu rida - see on errorit väärt
			 */
			if($nrOfUpdated != 1) {
			    $nrOfUpdated = $table->fetchRow($table->select()->from($name,array('count' => 'count(*)'))->where('id = ?', $this->getId()))->count;
			    if($nrOfUpdated != 1) throw new Zend_Exception('Save error for class '.get_class($this).' with id '.$this->getId().': '.$nrOfUpdated.' updated');
			}
		}
    }
    
    public function delete()
    {
        $db = Reusable_Db_Registry::getDb();
        if($this->chkIsPresisted()){   
            $db->delete($this->getTableName(), $db->quoteInto('id = ?', $this->getId()));
            $this->id = null;
        }
    }
       
    public function mapArrayToParams(array $conf)
    {
        $newConf = array();
        foreach ($conf as $name => $value){
            $newConf[$this->toCamelCase($name)] = $value;   
        }
        parent::mapArrayToParams($newConf);
    }
        
    
    /**
     * This must map params to array that can be saved as mysql row
     * We need this, because mysql has more strict rules for values than we would like for our object properties
     * Plus we might not want to presist all properties
     * @return array
     */
    protected function mapParamsToMyArray(){
        $data = $this->mapParamsToArray();
        $myData = array();
        foreach ($data as $name => $value) {
        	$myData[$this->fromCamelCase($name)] = $value;
        }
        return $myData;
    }
    
    protected function getTableName()
    {
        $name = array_pop((explode('_',get_class($this))));
	    $name = $this->fromCamelCase($name);
	    return $name;
    }
    
    protected function fromCamelCase($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }
    
    protected function toCamelCase($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
}
