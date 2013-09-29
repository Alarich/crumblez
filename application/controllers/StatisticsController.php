<?php

class IndexController extends Zend_Controller_Action
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
    }


}

