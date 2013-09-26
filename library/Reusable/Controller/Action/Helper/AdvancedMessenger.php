<?php
class Reusable_Controller_Action_Helper_AdvancedMessenger extends Zend_Controller_Action_Helper_Abstract
{
	protected $acceptErrors = array();
    protected $acceptMessages = array();
	static protected $loggingEnabled = false;
	protected $session;
	protected $defaultValues = array();
	
	public function __construct($namespace = null)
	{
	    $this->defaultValues = Zend_Registry::getInstance()->isRegistered('defaultErrorsAndMessages') ? Zend_Registry::getInstance()->get('defaultErrorsAndMessages') : array();
	    if($namespace) parent::setNamespace($namespace);
	    $this->session = Zend_Registry::getInstance()->get('session');
	    if(
	        !is_array($this->session->other) ||
	        !is_array($this->session->messages) ||
	        !is_array($this->session->errors)
	    ) $this->clear();
	}
		
	public function getMessages()
	{
	    $result = $this->session->messages;
	    $this->session->messages = array();
	    return $result;
	}
	
	public function setMessage($messageId)
	{
	    $this->session->messages[$messageId] = isset($this->defaultValues[$messageId]) ? $this->defaultValues[$messageId] : true;
	}
	
	public function getErrors()
	{
		$result = $this->session->errors;
	    $this->session->errors = array();
	    return $result;
	}
	
	public function setError($errorId)
	{
	    $this->session->errors[$errorId] = isset($this->defaultValues[$errorId]) ? $this->defaultValues[$errorId] : true;
	}
	
	public function setErrors(array $errorIds)
	{
	    foreach ($errorIds as $errorId) {
	    	$this->setError($errorId);
	    }
	}
	
	public function set($name,$value)
	{
	    if(!isset($value)) unset($this->session->other[$name]);
		$this->session->other[$name] = $value;
	}
	
	public function has($name, $type = null, $maxNumeric = null)
	{
	    if(self::$loggingEnabled) Zend_Wildfire_Plugin_FirePhp::getInstance()->send($name,'AdvancedMessenger checking for:');
		if(!isset($this->session->other[$name])) return false;
	    if(isset($type) && !($this->session->other[$name] instanceof $type)) return false;
	    if(isset($maxNumeric) && is_numeric($this->session->other[$name]) && $this->session->other[$name] > $maxNumeric) return false;
	    return true;
	}
	
	public function get($name, $onlyLook = false)
	{
		$result = $this->session->other[$name];
		if(!$onlyLook) unset($this->session->other[$name]);
		return $result;
	}
	
	public function getIf($name,$default,$onlyLook = false, $type=null, $maxNumeric = null)
	{
	    if($this->has($name,$type, $maxNumeric)) return $this->get($name,$onlyLook);
	    else return $default;
	}
	
	public function clear()
    {
        $this->session->other = array();
        $this->session->messages = array();
        $this->session->errors = array();
    }
	
	public static function enableFirephpLogging()
	{
	    self::$loggingEnabled = true;
	}
}