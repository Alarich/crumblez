<?php

class Crumblez_Model_Calculator extends Reusable_Model_Object_MyPresistable_Abstract 
{
	
	/**
	 * 
	 * @param Array $unitNumbers structure|Array('unitType'=>number,'unitType2'=>number2, ...);
	 * @param Array $extraParams structure|Array('conscription'=>false,'mathematics'=>false,'trainer'=>false,'shipwright'=>false)
	 */
	public static function calculateUnitCost($unitNumbers = array(),$extraParams = array())
	{
		if(!$unitNumbers)return false;
		if(!is_array($unitNumbers)) return false;
		$resourcesTotal = $resources = array('wood'=>0,'stone'=>0,'iron'=>0,'build_time'=>0,'favor'=>0);
		$resourcesPerUnit = array();
		$unitsData = Crumblez_Model_Units::getUnitData();
		foreach($unitNumbers as $unit=>$amount){
			$unitData = $unitsData[$unit];
			switch($unitData['type']){
				case 'land':
					$costReduction = isset($extraParams['conscription'])?$extraParams['conscription']:false;
					$speedReduction = isset($extraParams['trainer'])?$extraParams['trainer']:false;
					break;
				case 'naval':
					$costReduction = isset($extraParams['mathematics'])?$extraParams['mathematics']:false;
					$speedReduction = isset($extraParams['shipwright'])?$extraParams['shipwright']:false;
					break;
			}
			$resources['wood'] = $unitData['resources']['wood']*$amount*($costReduction?0.9:1);
			$resources['stone'] = $unitData['resources']['stone']*$amount*($costReduction?0.9:1);
			$resources['iron'] = $unitData['resources']['iron']*$amount*($costReduction?0.9:1);
			$resources['favor'] = $unitData['resources']['favor']*$amount*($costReduction?0.9:1);
			$resources['build_time'] = $unitData['build_time']*$amount*$amount*($speedReduction?0.9:1);
			foreach($resources as $type=>$amount)$resourcesTotal[$type] += $amount;
			$resourcesPerUnit[$unit] = $resources;
		}
		$resourcesPerUnit['total'] = $resourcesTotal;
		return $resourcesPerUnit;
	}
	
	
}

?>