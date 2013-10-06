<?php

class StatisticsController extends Zend_Controller_Action
{
	/**
	 * @var Arve_Controller_Action_Helper_AdvancedMessenger
	 */
	public $messenger;
	
	public function init()
	{
		$this->messenger = $this->_helper->getHelper('AdvancedMessenger');
	}
	

    public function indexAction()
    {
        // action body
        $this->view->realAllianceBPNumbers = Crumblez_Model_Statistics::getAllianceRealBPNumbers(array(24,3939));
    }
    
    

    public function testAction()
    {
    	$test = file_get_contents('json.txt');
    	$data = json_decode($test);
    	foreach($data as $dat){
    		echo "
    		'".$dat->id."' => array(
    			'attack_type' => '".$dat->attack_type."',
    			'attack' => '".$dat->attack."',
    			'def_hack' => '".$dat->def_hack."',
    			'def_pierce' => '".$dat->def_pierce."',
    			'def_distance' => '".$dat->def_distance."',
    			'booty' => '".$dat->booty."',
    			'speed' => '".$dat->speed."',
    			'population' => '".$dat->population."',
    			'build_time' => '".$dat->build_time."',
    			'god_id' => '".$dat->god_id."',
    			'type' => '".($dat->is_naval?'naval':'land')."',
    			'flying' => '".$dat->flying."',
    			'resources' => array(
    				'wood' => ".($dat->resources->wood?$dat->resources->wood:0).",
    				'stone' => ".($dat->resources->stone?$dat->resources->stone:0).",
    				'iron' => ".($dat->resources->iron?$dat->resources->iron:0).",
    				'favor' => ".($dat->favor?$dat->favor:0).",
    			)
    		),";
    	}
    	die;
    }
    
    public function calculatorTestAction()
    {
    	$returnArr = Crumblez_Model_Calculator::calculateUnitCost(array('archer'=>1000,'sword'=>1000),array('conscription'=>true));
    	echo "<pre>";
    	var_dump($returnArr);
    	echo "</pre>";
    	die;
    }

}

