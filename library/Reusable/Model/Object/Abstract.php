<?php
abstract class Reusable_Model_Object_Abstract
{
    /**
     * Map array $conf to public and non-public params
     * @param array $conf
     */
    public function __construct(array $conf = array())
    {       
       //map private and protected attributes
       $properties = $this->getNonpublicProperties();
       foreach ($properties as $name => $value){
           //DENY: not in conf
           if(!isset($conf[$name]) && !isset($conf[strtolower($name)])) continue;
           
           //set/unset property
           if(is_string($conf[$name]) && trim($conf[$name]) == '') $this->{$name} = NULL;
	       else $this->{$name} = isset($conf[$name]) ? $conf[$name] : $conf[strtolower($name)];
           
           //remove from conf, because we dont want it set second time by setter method
           unset($conf[$name]);
       }
       
       //map public attributes
       $this->mapArrayToParams($conf);
    }
    
    /**
     * Map array $conf to public params
     * @param array $conf
     */
    public function mapArrayToParams(array $conf)
    {
        $properties = Reusable_Model_Object::getPublicObjectVars($this);
        foreach ($properties as $name => $value) {
            //DENY: not in conf
            if(!isset($conf[$name]) && !isset($conf[strtolower($name)])) continue;
           
            //set/unset property
            if(is_string($conf[$name]) && trim($conf[$name]) == '') $this->{$name} = NULL;
	        else $this->{$name} = isset($conf[$name]) ? $conf[$name] : $conf[strtolower($name)];
        }
    }
    
    /**
     * Map public params to array
     * return array
     */
    public function mapParamsToArray(){
        return Reusable_Model_Object::getPublicObjectVars($this);
    }
    
    private function getNonpublicProperties(){
        return array_diff_key(get_object_vars($this),Reusable_Model_Object::getPublicObjectVars($this));
	}
}
