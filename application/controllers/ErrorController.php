<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            	$this->getResponse()->setHttpResponseCode(404);
                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setScriptAction('error404');
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = $this->view->translate('Error occured.');
		        $this->view->exception = $errors->exception;
		        $this->view->request = $errors->request;
		        
		        //if(getenv('APPLICATION_ENV') != 'development') if(getenv('APPLICATION_ENV') != 'development') Cactus_Model_ExceptionRegister::log($errors->exception, $errors->request);
             break;
        }
        
    }
}

