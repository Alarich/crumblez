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
    
    public function importPlayerListAction()
    {
    	$mtime = microtime(true);
    	$n = 0;
    	$link = 'http://en56.grepolis.com/data/players.txt.gz';
    	$config = Zend_Registry::get('config');
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filename = $config->serverdata->path."EN56/players.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$lines = gzfile($filename);    	 
    	foreach($lines as $line){
    		$n++;
    		$data = explode(',',$line);
    		$existing = Crumblez_Model_Player::getObjectId((int)trim($data[0]));
    		if($existing)$player = Crumblez_Model_Player::get((int)$existing);
    		else $player = new Crumblez_Model_Player(array());
    		$player->playerId = trim($data[0]);
    		$player->name = str_replace('+',' ',trim($data[1]));
    		$player->allianceId = trim($data[2]);
    		$player->points = trim($data[3]);
    		$player->rank = trim($data[4]);
    		$player->towns = trim($data[5]);
    		$player->save();
    	}
    	 
    	die("Updated ".$n." players. It took ".(microtime(true)-$mtime)." seconds");
    }
    
    public function importAllianceListAction()
    {
    	$mtime = microtime(true);
    	$n = 0;
    	$link = 'http://en56.grepolis.com/data/alliances.txt.gz';
    	$config = Zend_Registry::get('config');
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filename = $config->serverdata->path."EN56/alliances.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$lines = gzfile($filename);    	 
    	foreach($lines as $line){
    		$n++;
    		$data = explode(',',$line);
    		$existing = Crumblez_Model_Alliance::getObjectId((int)trim($data[0]));
    		if($existing)$alliance = Crumblez_Model_Alliance::get((int)$existing);
    		else $alliance = new Crumblez_Model_Alliance(array());
    		$alliance->allianceId = trim($data[0]);
    		$alliance->name = str_replace('+',' ',trim($data[1]));
    		$alliance->points = trim($data[2]);
    		$alliance->towns = trim($data[3]);
    		$alliance->members = trim($data[4]);
    		$alliance->rank = trim($data[5]);
    		$alliance->save();
    	}
    	 
    	die("Updated ".$n." alliances. It took ".(microtime(true)-$mtime)." seconds");
    }
    
    public function importTownListAction()
    {
    	$mtime = microtime(true);
    	$n = 0;
    	$link = 'http://en56.grepolis.com/data/towns.txt.gz';
    	$config = Zend_Registry::get('config');
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filename = $config->serverdata->path."EN56/towns.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$lines = gzfile($filename);
    	foreach($lines as $line){
    		$n++;
    		$data = explode(',',$line);
    		$existing = Crumblez_Model_Town::getObjectId((int)trim($data[0]));
    		if($existing)$town = Crumblez_Model_Town::get((int)$existing);
    		else $town = new Crumblez_Model_Town(array());
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
    
    
    public function importConquerListAction()
    {
    	$mtime = microtime(true);
    	$n = 0;
    	$link = 'http://en56.grepolis.com/data/conquers.txt.gz';
    	$config = Zend_Registry::get('config');
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filename = $config->serverdata->path."EN56/conquers.txt.".time().".gz";
    	file_put_contents($filename,file_get_contents($link));
    	$lines = gzfile($filename);
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
    	$mtime = microtime(true);
	    $n = 0;
    	if(!Crumblez_Model_Island::chkStatus()){//if islands already exist, don't download again.
	    	$link = 'http://en56.grepolis.com/data/islands.txt.gz';
	    	$config = Zend_Registry::get('config');
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
    
    public function importPlayerBattlePointListAction()
    {
    	$mtime = microtime(true);
    	$n = 0;
    	$bpTotalLink = 'http://en56.grepolis.com/data/player_kills_all.txt.gz';
    	$bpALink = 'http://en56.grepolis.com/data/player_kills_att.txt.gz';
    	$bpDLink = 'http://en56.grepolis.com/data/player_kills_def.txt.gz';
    	 
    	$config = Zend_Registry::get('config');
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filenameT = $config->serverdata->path."EN56/player_kills_all.txt.".time().".gz";
    	file_put_contents($filenameT,file_get_contents($bpTotalLink));
    	$filenameA = $config->serverdata->path."EN56/player_kills_att.txt.".time().".gz";
    	file_put_contents($filenameA,file_get_contents($bpALink));
    	$filenameD = $config->serverdata->path."EN56/player_kills_def.txt.".time().".gz";
    	file_put_contents($filenameD,file_get_contents($bpDLink));
    	$linesT = gzfile($filenameT);
    	$linesA = gzfile($filenameA);
    	$linesD = gzfile($filenameD);
    	$BPArray = array();
    	foreach($linesT as $line){
    		$data = explode(',',$line);
    		$BPArray[$data[1]]['bp_rank'] = trim($data[0]);
    		$BPArray[$data[1]]['bp'] = trim($data[2]);
    	}
    	foreach($linesA as $line){
    		$data = explode(',',$line);
    		$BPArray[$data[1]]['abp_rank'] = trim($data[0]);
    		$BPArray[$data[1]]['abp'] = trim($data[2]);
    	}
    	foreach($linesD as $line){
    		$data = explode(',',$line);
    		$BPArray[$data[1]]['dbp_rank'] = trim($data[0]);
    		$BPArray[$data[1]]['dbp'] = trim($data[2]);
    	}
    	 
    	foreach($BPArray as $playerId=>$data){
    		$n++;
    		$battlePointsPlayer = new Crumblez_Model_BattlePointsPlayer(array());
    		$battlePointsPlayer->playerId = $playerId;
    		$battlePointsPlayer->time = time();
    		$battlePointsPlayer->points = $data['bp'];
    		$battlePointsPlayer->pointsAttack = $data['abp'];
    		$battlePointsPlayer->pointsDefense = $data['dbp'];
    		$battlePointsPlayer->pointsRank = $data['bp_rank'];
    		$battlePointsPlayer->pointsAttackRank = $data['abp_rank'];
    		$battlePointsPlayer->pointsDefenseRank = $data['dbp_rank'];
    		$battlePointsPlayer->save();
    	}
    
    	die("Updated ".$n." Player BP listings. It took ".(microtime(true)-$mtime)." seconds");
    }
    
    public function importAllianceBattlePointListAction()
    {
    	$mtime = microtime(true);
    	$n = 0;
    	$bpTotalLink = 'http://en56.grepolis.com/data/alliance_kills_all.txt.gz';
    	$bpALink = 'http://en56.grepolis.com/data/alliance_kills_att.txt.gz';
    	$bpDLink = 'http://en56.grepolis.com/data/alliance_kills_def.txt.gz';
    	 
    	$config = Zend_Registry::get('config');
    	if(!is_dir($config->serverdata->path."EN56/")){
    		mkdir($config->serverdata->path."EN56/");
    	}
    	$filenameT = $config->serverdata->path."EN56/alliance_kills_all.txt.".time().".gz";
    	file_put_contents($filenameT,file_get_contents($bpTotalLink));
    	$filenameA = $config->serverdata->path."EN56/alliance_kills_att.txt.".time().".gz";
    	file_put_contents($filenameA,file_get_contents($bpALink));
    	$filenameD = $config->serverdata->path."EN56/alliance_kills_def.txt.".time().".gz";
    	file_put_contents($filenameD,file_get_contents($bpDLink));
    	$linesT = gzfile($filenameT);
    	$linesA = gzfile($filenameA);
    	$linesD = gzfile($filenameD);
    	$BPArray = array();
    	foreach($linesT as $line){
    		$data = explode(',',$line);
    		$BPArray[$data[1]]['bp_rank'] = trim($data[0]);
    		$BPArray[$data[1]]['bp'] = trim($data[2]);
    	}
    	foreach($linesA as $line){
    		$data = explode(',',$line);
    		$BPArray[$data[1]]['abp_rank'] = trim($data[0]);
    		$BPArray[$data[1]]['abp'] = trim($data[2]);
    	}
    	foreach($linesD as $line){
    		$data = explode(',',$line);
    		$BPArray[$data[1]]['dbp_rank'] = trim($data[0]);
    		$BPArray[$data[1]]['dbp'] = trim($data[2]);
    	}
    	 
    	foreach($BPArray as $allianceId=>$data){
    		$n++;
    		$battlePointsPlayer = new Crumblez_Model_BattlePointsAlliance(array());
    		$battlePointsPlayer->allianceId = $allianceId;
    		$battlePointsPlayer->time = time();
    		$battlePointsPlayer->points = $data['bp'];
    		$battlePointsPlayer->pointsAttack = $data['abp'];
    		$battlePointsPlayer->pointsDefense = $data['dbp'];
    		$battlePointsPlayer->pointsRank = $data['bp_rank'];
    		$battlePointsPlayer->pointsAttackRank = $data['abp_rank'];
    		$battlePointsPlayer->pointsDefenseRank = $data['dbp_rank'];
    		$battlePointsPlayer->save();
    	}
    
    	die("Updated ".$n." Alliance BP listings. It took ".(microtime(true)-$mtime)." seconds");
    }
    
}

