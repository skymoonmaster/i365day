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
        
        $userInfoJson = $douban->api('User.me.GET')->makeRequest();

        $doubanUserInfo = json_decode($userInfoJson, true);

        $userInfo = array();
        $userInfo['app_id'] = Conf_Oauth::$appIds['douban'];
        $userInfo['app_uid'] = $doubanUserInfo['id'];
        $userInfo['head_portrait'] = $doubanUserInfo['avatar'];
        $userInfo['country'] = '';
        $userInfo['city'] = $doubanUserInfo['loc_name'];
        $userInfo['nick_name'] = $doubanUserInfo['name'];
        $userInfo['intro'] = $doubanUserInfo['desc'];

        $_SESSION['user_info'] = $userInfo;

        $this->redirect("/login/doLogin");
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>