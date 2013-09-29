<?php

class ImportController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
        
    public function importTownsAction()
    {
    	$config = Zend_Registry::get('config');
    	if($this->_getParam('cronkey') != $config->cronkey || !$this->_getParam('cronkey')) throw new Zend_Controller_Action_Exception('This page dont exist',404);
    	$mtime = microtime(true);
    	$n = 0;
    	$link = 'http://en56.grepolis.com/data/towns.txt.gz';
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filename = $config->serverdata->path."EN56/towns.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$lines = gzfile($filename);
    	unlink($filename);
    	foreach($lines as $line){
    		$n++;
    		$data = explode(',',$line);
    		$existing = Crumblez_Model_Town::getObjectId((int)trim($data[0]));
    		if($existing)$town = Crumblez_Model_Town::get((int)$existing);
    		else $town = new Crumblez_Model_Town(array());
    		if(!$town->getId())$town->timeFounded = time();
    		$town->townId = trim($data[0]);
    		$town->playerId = trim($data[1]);
    		$town->name = str_replace('+',' ',trim($data[2]));
    		$town->islandX = trim($data[3]);
    		$town->islandY = trim($data[4]);
    		$town->numberOnIsland = trim($data[5]);
    		$town->points = trim($data[6]);
    		$town->save();
    	}
    	 
    	die("Updated ".$n." towns. It took ".(microtime(true)-$mtime)." seconds");
    }
    
    
    public function importConquersAction()
    {
    	$config = Zend_Registry::get('config');
    	if($this->_getParam('cronkey') != $config->cronkey || !$this->_getParam('cronkey')) throw new Zend_Controller_Action_Exception('This page dont exist',404);
    	$mtime = microtime(true);
    	$n = 0;
    	$link = 'http://en56.grepolis.com/data/conquers.txt.gz';
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filename = $config->serverdata->path."EN56/conquers.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$lines = gzfile($filename);
    	unlink($filename);
    	Crumblez_Model_Conquer::clearTable();
    	foreach($lines as $line){
    		$n++;
    		$data = explode(',',$line);
    		$conquer = new Crumblez_Model_Conquer(array());
    		$conquer->townId = trim($data[0]);
    		$conquer->time = trim($data[1]);
    		$conquer->newPlayerId = trim($data[2]);
    		$conquer->oldPlayerId = trim($data[3]);
    		$conquer->newAllianceId = trim($data[4]);
    		$conquer->oldAllianceId = trim($data[5]);
    		$conquer->townPoints = trim($data[6]);
    		$conquer->save();
    	}
    	 
    	die("Updated ".$n." conquers. It took ".(microtime(true)-$mtime)." seconds");
    }
    
    public function importIslandListAction()
    {
    	$config = Zend_Registry::get('config');
    	if($this->_getParam('cronkey') != $config->cronkey || !$this->_getParam('cronkey')) throw new Zend_Controller_Action_Exception('This page dont exist',404);
    	$mtime = microtime(true);
	    $n = 0;
    	if(!Crumblez_Model_Island::chkStatus()){//if islands already exist, don't download again.
	    	$link = 'http://en56.grepolis.com/data/islands.txt.gz';
	    	if(!is_dir($config->serverdata->path."EN56/")){
	    		mkdir($config->serverdata->path."EN56/");
	    	}
	    	$filename = $config->serverdata->path."EN56/islands.txt.gz";
	    	file_put_contents($filename,file_get_contents($link));
	    	$lines = gzfile($filename);
	    	foreach($lines as $line){
	    		$n++;
	    		$data = explode(',',$line);
	    		$island = new Crumblez_Model_Island(array());
	    		$island->islandId = trim($data[0]);
	    		$island->x = trim($data[1]);
	    		$island->y = trim($data[2]);
	    		$island->islandType = trim($data[3]);
	    		$island->availableTowns =trim( $data[4]);
	    		$island->save();
	    	}
    	}
    	die("Updated ".$n." islands. It took ".(microtime(true)-$mtime)." seconds");
    }
    
    
    public function importAllianceAction()
    {
    	$config = Zend_Registry::get('config');
    	if($this->_getParam('cronkey') != $config->cronkey || !$this->_getParam('cronkey')) throw new Zend_Controller_Action_Exception('This page dont exist',404);
    	$mtime = microtime(true);
    	$n = 0;
    	$bpTotalLink = 'http://en56.grepolis.com/data/alliance_kills_all.txt.gz';
    	$bpALink = 'http://en56.grepolis.com/data/alliance_kills_att.txt.gz';
    	$bpDLink = 'http://en56.grepolis.com/data/alliance_kills_def.txt.gz';
    	$link = 'http://en56.grepolis.com/data/alliances.txt.gz';
    	$time = time();
    	
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filenameT = $config->serverdata->path."EN56/alliance_kills_all.txt.".time().".gz";
    	file_put_contents($filenameT,file_get_contents($bpTotalLink));
    	$filenameA = $config->serverdata->path."EN56/alliance_kills_att.txt.".time().".gz";
    	file_put_contents($filenameA,file_get_contents($bpALink));
    	$filenameD = $config->serverdata->path."EN56/alliance_kills_def.txt.".time().".gz";
    	file_put_contents($filenameD,file_get_contents($bpDLink));
    	$filename = $config->serverdata->path."EN56/alliances.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$linesT = gzfile($filenameT);
    	$linesA = gzfile($filenameA);
    	$linesD = gzfile($filenameD);
    	$lines = gzfile($filename);
    	unlink($filenameT);
    	unlink($filenameA);
    	unlink($filenameD);
    	unlink($filename);
    	$AllianceArray = array();
    	foreach($linesT as $line){
    		$data = explode(',',$line);
    		$AllianceArray[$data[1]]['bp_rank'] = trim($data[0]);
    		$AllianceArray[$data[1]]['bp'] = trim($data[2]);
    	}
    	foreach($linesA as $line){
    		$data = explode(',',$line);
    		$AllianceArray[$data[1]]['abp_rank'] = trim($data[0]);
    		$AllianceArray[$data[1]]['abp'] = trim($data[2]);
    	}
    	foreach($linesD as $line){
    		$data = explode(',',$line);
    		$AllianceArray[$data[1]]['dbp_rank'] = trim($data[0]);
    		$AllianceArray[$data[1]]['dbp'] = trim($data[2]);
    	}
    	
    	foreach($lines as $line){
    		$data = explode(',',$line);
    		$AllianceArray[$data[0]]['name'] = str_replace('+',' ',trim($data[1]));
    		$AllianceArray[$data[0]]['points'] = trim($data[2]);
    		$AllianceArray[$data[0]]['towns'] = trim($data[3]);
    		$AllianceArray[$data[0]]['members'] = trim($data[4]);
    		$AllianceArray[$data[0]]['rank'] = trim($data[5]);
    	}
    	    	
    	foreach($AllianceArray as $allianceId=>$data){
    		$allianceLatest = Crumblez_Model_Alliance::getLatestAllianceData($allianceId);
    		if(
    				$allianceLatest->name==$data['name'] &&
    				$allianceLatest->towns==$data['towns'] &&
    				$allianceLatest->members==$data['members'] &&
    				$allianceLatest->points==$data['points'] &&
    				$allianceLatest->pointsBattle==$data['bp'] &&
    				$allianceLatest->pointsAttack==$data['abp'] &&
    				$allianceLatest->pointsDefense==$data['dbp'] &&
    				$allianceLatest->pointsRank==$data['rank'] &&
    				$allianceLatest->pointsBattleRank==$data['bp_rank'] &&
    				$allianceLatest->pointsAttackRank==$data['abp_rank'] &&
    				$allianceLatest->pointsDefenseRank==$data['dbp_rank']
    		){
    			continue;
    		}
    		$n++;
    		$alliance = new Crumblez_Model_Alliance(array());
    		$alliance->allianceId = $allianceId;
    		$alliance->time = $time;
    		$alliance->name = $data['name'];
    		$alliance->points = $data['points'];
    		$alliance->towns = $data['towns'];
    		$alliance->members = $data['members'];
    		$alliance->points = $data['points'];
    		$alliance->pointsBattle = $data['bp'];
    		$alliance->pointsAttack = $data['abp'];
    		$alliance->pointsDefense = $data['dbp'];
    		$alliance->pointsRank = $data['rank'];
    		$alliance->pointsBattleRank = $data['bp_rank'];
    		$alliance->pointsAttackRank = $data['abp_rank'];
    		$alliance->pointsDefenseRank = $data['dbp_rank'];
    		$alliance->save();
    	}
    
    	die("Updated ".$n." Alliance listings. It took ".(microtime(true)-$mtime)." seconds");
    }
    
    
    public function importPlayerAction()
    {
    	$config = Zend_Registry::get('config');
    	if($this->_getParam('cronkey') != $config->cronkey || !$this->_getParam('cronkey')) throw new Zend_Controller_Action_Exception('This page dont exist',404);
    	$mtime = microtime(true);
    	$n = 0;
    	$bpTotalLink = 'http://en56.grepolis.com/data/player_kills_all.txt.gz';
    	$bpALink = 'http://en56.grepolis.com/data/player_kills_att.txt.gz';
    	$bpDLink = 'http://en56.grepolis.com/data/player_kills_def.txt.gz';
    	$link = 'http://en56.grepolis.com/data/players.txt.gz';
    	$time = time();
    	
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filenameT = $config->serverdata->path."EN56/player_kills_all.txt.".time().".gz";
    	file_put_contents($filenameT,file_get_contents($bpTotalLink));
    	$filenameA = $config->serverdata->path."EN56/player_kills_att.txt.".time().".gz";
    	file_put_contents($filenameA,file_get_contents($bpALink));
    	$filenameD = $config->serverdata->path."EN56/player_kills_def.txt.".time().".gz";
    	file_put_contents($filenameD,file_get_contents($bpDLink));
    	$filename = $config->serverdata->path."EN56/players.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$linesT = gzfile($filenameT);
    	$linesA = gzfile($filenameA);
    	$linesD = gzfile($filenameD);
    	$lines = gzfile($filename);
    	unlink($filenameT);
    	unlink($filenameA);
    	unlink($filenameD);
    	unlink($filename);
    	$PlayerArray = array();
    	foreach($linesT as $line){
    		$data = explode(',',$line);
    		$PlayerArray[$data[1]]['bp_rank'] = trim($data[0]);
    		$PlayerArray[$data[1]]['bp'] = trim($data[2]);
    	}
    	foreach($linesA as $line){
    		$data = explode(',',$line);
    		$PlayerArray[$data[1]]['abp_rank'] = trim($data[0]);
    		$PlayerArray[$data[1]]['abp'] = trim($data[2]);
    	}
    	foreach($linesD as $line){
    		$data = explode(',',$line);
    		$PlayerArray[$data[1]]['dbp_rank'] = trim($data[0]);
    		$PlayerArray[$data[1]]['dbp'] = trim($data[2]);
    	}
    	foreach($lines as $line){
    		$data = explode(',',$line);
    		$PlayerArray[$data[0]]['name'] = str_replace('+',' ',trim($data[1]));
    		$PlayerArray[$data[0]]['allianceId'] = $data[2]?trim($data[2]):null;
    		$PlayerArray[$data[0]]['points'] = trim($data[3]);
    		$PlayerArray[$data[0]]['rank'] = trim($data[4]);
    		$PlayerArray[$data[0]]['towns'] = trim($data[5]);
    	}
    		
    	
    	foreach($PlayerArray as $playerId=>$data){
    		$playerLatest = Crumblez_Model_Player::getLatestPlayerData($playerId);
    		if(
    				$playerLatest->name==$data['name'] &&
    				$playerLatest->allianceId==$data['allianceId'] &&
    				$playerLatest->towns==$data['towns'] &&
    				$playerLatest->points==$data['points'] &&
    				$playerLatest->pointsBattle==$data['bp'] &&
    				$playerLatest->pointsAttack==$data['abp'] &&
    				$playerLatest->pointsDefense==$data['dbp'] &&
    				$playerLatest->pointsRank==$data['rank'] &&
    				$playerLatest->pointsBattleRank==$data['bp_rank'] &&
    				$playerLatest->pointsAttackRank==$data['abp_rank'] &&
    				$playerLatest->pointsDefenseRank==$data['dbp_rank']
    		){
    			continue;
    		}
    		$n++;
    		$player = new Crumblez_Model_Player(array());
    		$player->playerId = $playerId;
    		$player->allianceId = $data['allianceId'];
    		$player->time = $time;
    		$player->name = $data['name'];
    		$player->points = $data['points'];
    		$player->towns = $data['towns'];
    		$player->points = $data['points'];
    		$player->pointsBattle = $data['bp'];
    		$player->pointsAttack = $data['abp'];
    		$player->pointsDefense = $data['dbp'];
    		$player->pointsRank = $data['rank'];
    		$player->pointsBattleRank = $data['bp_rank'];
    		$player->pointsAttackRank = $data['abp_rank'];
    		$player->pointsDefenseRank = $data['dbp_rank'];
    		$player->save();
    	}
    
    	die("Updated ".$n." Player listings. It took ".(microtime(true)-$mtime)." seconds");
    }
    
}

