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
class CallbackController extends BasicController {

    public function doubanAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $douban = Oauth_Adapter::getInstance()->createOauthModel('douban');
        // 如果没有authorizeCode，跳转到用户授权页面
        if (!isset($_GET['code'])) {
            $douban->requestAuthorizeCode();
            exit;
        }
        // 设置authorizeCode
        $douban->setAuthorizeCode($_GET['code']);
        // 通过authorizeCode获取accessToken，至此完成用户授权
        $douban->requestAccessToken();
        
        $userInfo = $douban->api('User.me.GET')->makeRequest();
        $userInfo['app_id'] = Conf_Oauth::$appIds['douban'];
        $userInfo['app_uid'] = $userInfo['id'];

        $_SESSION['oauth_user_info'] = $userInfo;

        $this->redirect("/login/doLogin");
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>