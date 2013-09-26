<?php
class Reusable_Db_Registry
{
    protected static $instances = array();
    
    /**
     *
     * @param unknown_type $name
     * @return Zend_Db_Table
     */
    public static function &getTableInstance($name)
    {
        if(!isset(self::$instances[$name])) self::$instances[$name] = new Zend_Db_Table($name);
        if(self::$instances[$name] instanceof Zend_Db_Table) return self::$instances[$name];
        else throw new Exception('Table not initiated');
    }
    
    /**
     * @return Zend_Db_Adapter_Abstract
     */
    public static function &getDb()
    {
        $db = Zend_Registry::getInstance()->get('db');
        if(!$db instanceof Zend_Db_Adapter_Abstract) throw new Exception('No db found in registry');
        else return $db;
    }
}