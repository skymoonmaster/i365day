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
class OauthController extends BasicController {

    public function doubanAction() {
        $oauthModel = Oauth_Adapter::getInstance()->createOauthModel('douban');
        
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>