<?php
/**
 * @TODO: Something for deleting older server data collections, keep maybe 30 days, will see how much data that is. - Doesn't appear to be an issue.
 * 
 * @author Alarich
 *
 */
class CronController extends Zend_Controller_Action
{

    public function init()
    {
    }

	public function cronAction()
	{
		$cron = Crumblez_Model_Cronjob::getOneIncomplete();
		if($cron){
			$this->_helper->redirector->gotoUrl($cron->url.'cron/'.$cron->getId());
		}
		die();
	}
	
	public function automaticCronAction()
	{
		$params = $this->_getAllParams();
		switch($params['act']){
			case 'player_update_failure':
				$config = Zend_Registry::get('config');
				$this->_helper->redirector->gotoSimple('import-player','import',null,array('cronkey'=>$config->cronkey));
			break;
			case 'alliance_update_failure':
				$config = Zend_Registry::get('config');
				$this->_helper->redirector->gotoSimple('import-alliance','import',null,array('cronkey'=>$config->cronkey));
			break;
			case 'something_else':
				if(!$params['id'])die();
				try{
					
				}catch( Exception $e){
					$error = true;
				}
			break;			
		}
		if(!$error){
			Crumblez_Model_Cronjob::setComplete(intval($params['cron']));
		}
		
		die($error);
	}
	
}

