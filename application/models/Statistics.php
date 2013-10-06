<?php

class Crumblez_Model_Statistics extends Reusable_Model_Object_MyPresistable_Abstract 
{
	public static function getAllianceRealBPNumbers($allianceIds)
	{
		if(is_int($allianceIds))$allianceIds = array($allianceIds);
		if(is_array($allianceIds)){
			$db = Reusable_Db_Registry::getDb();
			$select = $db->query("
					SELECT 
						`a`.`alliance_id`, 
						`a`.`time`, 
						`a`.`name`, 
						`a`.`points_battle` AS BP, 
						`a`.`points_attack` AS ABP, 
						`a`.`points_defense` AS DBP, 
						`a`.`towns`, 
						`a`.`members`,
						`a`.`points_rank` AS rank
					FROM `alliance` AS `a` 
					INNER JOIN (SELECT id, alliance_id, MAX(time) as time FROM alliance GROUP BY alliance_id) AS `a2` ON a.alliance_id = a2.alliance_id AND a.time = a2.time 
					WHERE (a.points_rank < 11)
					ORDER BY a.points_rank
					
					");
			$rows = $select->fetchAll();
			
			foreach ($rows as $row){
				$select2 = $db->query("
						SELECT 
							SUM(`p`.`points_battle`) as BP_real,
							SUM(`p`.`points_attack`) as ABP_real,
							SUM(`p`.`points_defense`) as DBP_real
						FROM `player` AS `p`
						INNER JOIN (SELECT id,player_id, MAX(time) as time FROM player GROUP BY player_id) AS `p2` ON p.player_id = p2.player_id AND p.time = p2.time 
						WHERE `p`.`alliance_id` = '".$row['alliance_id']."'
						");
				$rows2 = $select2->fetchAll();			
				if($rows2[0]['BP_real']){
				foreach($rows2 as $row2){
					foreach($row2 as $key=>$item)$row[$key] = $item;
				}
				}else continue;//if no members are found, the alliance has disbanded.
				$result[$row['name']] = $row;
			}
			return $result;
		}else{
			return false;
		}
	}
	
}

?>