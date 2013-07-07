<?php

/* * *************************************************************************
 * 
 * Copyright (c) 2012 active.com, Inc. All Rights Reserved
 * 
 * ************************************************************************ */

/**
 * 
 * @package	library
 * @author	scao(sid.cao@activenetwork.com)
 * @version	$Revision: 1.1 $
 */
class Util_Curl {

    private static $instance = null;

    /**
     * @return Util_Curl
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Util_Curl();
        }
        return self::$instance;
    }

    public function initPostHandler($url, $data, $timeOut = 3) {
        $ch = curl_init();
        if (!$ch) {
            throw new Exception("init the curl handler error");
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        return $ch;
    }
    public function initGetHandler($url, $timeOut = 3) {
        $ch = curl_init();
        if (!$ch) {
            throw new Exception("init the curl handler error");
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        return $ch;
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>
