<?php
class Zend_View_Helper_ErrorsAndMessages extends Zend_View_Helper_Abstract
{
    protected $messageClass = 'success-message alert-box success';
    protected $errorClass = 'error-message alert-box alert';
  
    public function errorsAndMessages ($customValues = array(), $errors = null, $messages = null)
    {
        if($messages == null && isset($this->view->messages)) $messages = $this->view->messages;
        if($errors == null && isset($this->view->errors)) $errors = $this->view->errors;
        
        $result = '';
        if(is_array($messages) && !empty($messages)){
            $result .= '<div class="'.$this->messageClass.'">';
            foreach ($messages as $id => $message){
                if(!isset($this->acceptMessages)) $result .= '<span id="'.$id.'">'.(isset($customValues[$id]) ? $customValues[$id] : $message).'</span>';
            }
            $result .= '<a class="close">&times;</a></div>';
        }
        if(is_array($errors) && !empty($errors)){
            $result .= '<div class="'.$this->errorClass.'">';
            foreach ($errors as $id => $error){
                if(!isset($this->acceptMessages)) $result .= '<span id="'.$id.'">'.(isset($customValues[$id]) ? $customValues[$id] : $error).'</span>';
            }
            $result .= '<a class="close">&times;</a></div>';
        }
        
        return $result;
    }
}