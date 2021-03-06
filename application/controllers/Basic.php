<?php

/* * *************************************************************************
 * 
 * Copyright (c) 2013 i365.com, Inc. All Rights Reserved
 * 
 * ************************************************************************ */

/**
 * 
 * @package	controllers
 * @author	yp
 * @version	$Revision: 1.1 $
 */
class BasicController extends Yaf_Controller_Abstract {

    const ERROR_OK = 0;
    const ERROR_EMPTY = 1;
    const ERROR_INPUT_LENGTH = 10;
    protected $userInfo = array();

    protected function init() {
        $_SESSION['user_id'] = 1;
        if(isset($_SESSION['user_id']) && intval($_SESSION['user_id']) != 0){
            $this->userInfo = UserModel::getInstance()->getUserInfoById($_SESSION['user_id']);
        }
        //$this->userInfo = UserModel::getInstance()->getUserInfoById($_SESSION['user_id']);
    }

    protected function getAjaxParam($key) {
        $value = $this->getRequest()->get($key);
        if (!empty($value)) {
            $value = trim(urldecode($value));
        }
        return $value;
    }
    
    protected function getRequiredParam($key){
        $value = $this->getRequest()->get($key);
        if (is_null($value) || (!$value && $value != 0)) {
            throw new Exception_BadInput("$key can not be empty");
        }
        return $value;
    }
    
    protected function getOptionalParam($key, $default){
        $value = $this->getRequest()->get($key);
        if (!$value) {
            $value = $default;
        }
        return $value;
    }

}

?>