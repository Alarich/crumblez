<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initProfiler()
	{
		$resource = $this->getPluginResource('db');
		$db = $resource->getDbAdapter();
		if(isset($_REQUEST['profiler']) && $_REQUEST['profiler']=='active'){
			declare(ticks=1);
			require_once('DreamProfiler/DreamProfiler.php');
			include_once('DreamProfiler/Helper/DreamProfilerHelper.php');
		}
		Zend_Registry::getInstance()->set('db',$db);
	}
		
// 	protected function _initDb()
// 	{
		
// 		$resource = $this->getPluginResource('db');
// 		$db = $resource->getDbAdapter();
// 		Zend_Registry::getInstance()->set('db',$db);
// 	}
	
	protected function _initAutoload()
	{
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('Reusable_');
		$autoloader = new Zend_Application_Module_Autoloader(array(
				'namespace' => 'Crumblez_',
				'basePath'  => dirname(__FILE__),
		));
		if(class_exists('Crumblez_Model_ErrorsAndMessages')) Zend_Registry::getInstance()->set('defaultErrorsAndMessages', Crumblez_Model_ErrorsAndMessages::getDefaults());
	}
	
	protected function _initHelpers()
	{
		Zend_Controller_Action_HelperBroker::addPrefix('Reusable_Controller_Action_Helper');
	}
	
	protected function _initSession()
	{
		$general = new Zend_Session_Namespace('general');
		$auth = new Zend_Session_Namespace('auth');
		$admin = new Zend_Session_Namespace('admin');
		Zend_Registry::getInstance()->set('session',$general);
		Zend_Registry::getInstance()->set('authSession',$auth);
		Zend_Registry::getInstance()->set('adminSession',$admin);
	}
	
	protected function _initConfig()
	{
		$config = new Zend_Config_Ini(dirname(__FILE__).'/configs/config.ini','default');
		Zend_Registry::getInstance()->set('config',$config);
	}
	
	protected function _initAppPath()
	{
		Zend_Registry::getInstance()->set('appPath',dirname(__FILE__));
	}
	
	protected function _initEncoding()
	{
		mb_internal_encoding("UTF8");
	}
	
	protected function _initDateTime()
	{
		date_default_timezone_set('Europe/Berlin');
	}
	
	protected function _initRoutes()
	{
		$info = new Zend_Controller_Router_Route(
				'info/:tid',
				array(
						'controller' => 'info',
						'action' => 'index'
				)
		);
		$product = new Zend_Controller_Router_Route(
				'product/:url',
				array(
						'controller' => 'index',
						'action' => 'product'
				)
		);
			 
		Zend_Controller_Front::getInstance()->getRouter()->addRoute('product',$product);
		Zend_Controller_Front::getInstance()->getRouter()->addRoute('info',$info);
	}
	
	protected function _initLocale()
	{
	}
	
	protected function _initErrorReporting()
	{
		error_reporting(E_ALL - E_NOTICE);
	}
	
	protected function _initCache()
	{
		$config = Zend_Registry::get('config');
		$frontendOptions = array(
				'automatic_serialization' => true,
				'lifetime' => 3600
		);
		$backendOptions = array(
				'cache_dir' => $config->cache->path
		);
		$filecache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		Zend_Registry::set('filecache', $filecache);
	}

}

