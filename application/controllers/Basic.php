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
    protected $isSelf;

    protected function init() {
        $this->isSelf = true;
        $inputUserId = $this->getOptionalParam('p', 0, true);
//        $_SESSION['user_id'] = 1;
        $loginUserId = (isset($_SESSION['user_id']) && intval($_SESSION['user_id']) != 0) ? $_SESSION['user_id'] : 0;
        if ($loginUserId) {
            $this->userInfo = UserModel::getInstance()->getUserInfoById($loginUserId);
        }
        if($inputUserId && $loginUserId && $inputUserId != $loginUserId){
            $this->isSelf = false;
        }
        $this->getView()->assign('current_user_id', $inputUserId ? $inputUserId : $loginUserId);
        $this->getView()->assign('is_self', $this->isSelf);
    }

    protected function getAjaxParam($key) {
        $value = $this->getRequest()->get($key);
        if (!empty($value)) {
            $value = trim(urldecode($value));
        }
        return $value;
    }

    protected function getRequiredParam($key) {
        $value = $this->getRequest()->get($key);
        if (is_null($value) || (!$value && $value != 0)) {
            throw new Exception_BadInput("$key can not be empty");
        }
        return $value;
    }

    protected function getOptionalParam($key, $default, $isInt = false) {
        $value = $this->getRequest()->get($key);
        if($isInt){
            $value = intval($value);
        }
        if (!$value) {
            $value = $default;
        }
        return $value;
    }

    protected function getRefererRequiredParam($key) {
        $result = array();
        $referer = $_SERVER['HTTP_REFERER'];
        $refererInfo = parse_url($referer);
        $pathInfo = explode('/', $refererInfo['path']);
        array_shift($pathInfo);
        if (!$pathInfo || (count($pathInfo) % 2 != 0)) {
            return array();
        }
        $length = count($pathInfo);
        for ($index = 0; $index < $length; $index = $index + 2) {
            $result[$pathInfo[$index]] = $pathInfo[$index + 1];
        }
        if (!isset($result[$key]) || is_null($result[$key]) || (!$result[$key] && $result[$key] != 0)) {
            throw new Exception_BadInput("$key can not be empty");
        }
        return $result[$key];
    }

    protected function getRefererOptionalParam($key, $default, $isInt = false) {
        $result = array();
        $referer = $_SERVER['HTTP_REFERER'];
        $refererInfo = parse_url($referer);
        $pathInfo = explode('/', $refererInfo['path']);
        array_shift($pathInfo);
        if (!$pathInfo || (count($pathInfo) % 2 != 0)) {
            return $default;
        }
        $length = count($pathInfo);
        for ($index = 0; $index < $length; $index = $index + 2) {
            $result[$pathInfo[$index]] = $pathInfo[$index + 1];
        }
        if(isset($result[$key]) && $isInt){
            $result[$key] = intval($result[$key]);
        }
        if (!isset($result[$key]) || (!$result[$key])) {
            return $default;
        }
        return $result[$key];
    }

}

?>