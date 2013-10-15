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

    const ERROR_EMPTY = 1;
    const ERROR_INPUT_LENGTH = 10;

    protected function init() {
        $_SESSION['user_id'] = 1;
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
        if (!$value) {
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