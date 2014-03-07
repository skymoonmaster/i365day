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

    public function weiboAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        $code = $this->getRequiredParam('code');
        if (empty($code)) {
            $this->redirect('/index');

            return ;
        }

        $weibo = Oauth_Adapter::getInstance()->createOauthModel('weibo');

        $keys = array();
        $keys['code'] = $_REQUEST['code'];
        $keys['redirect_uri'] = Conf_Oauth::$weiboConf['redirect_uri'];
        $token = $weibo->getAccessToken('code', $keys) ;
        if (empty($token)) {
            $this->redirect('/index');

            return ;
        }

        $api = new SaeTClientV2(Conf_Oauth::$weiboConf['client_id'], Conf_Oauth::$weiboConf['secret'], $token['access_token']);
        $weiboUid = $token['uid'];

        $weiboUserInfo = $api->show_user_by_id($weiboUid);
        if (isset($weiboUserInfo['error_code']) && isset($weiboUserInfo['error'])) {
            $this->redirect('/index');

            return ;
        }

        $userInfo = array();
        $userInfo['app_id'] = Conf_Oauth::$appIds['weibo'];
        $userInfo['app_uid'] = $weiboUserInfo['id'];
        $userInfo['avatar'] = $weiboUserInfo['profile_image_url'];
        $userInfo['country'] = '';
        $userInfo['city'] = array_shift(explode(' ', $weiboUserInfo['location']));
        $userInfo['nick_name'] = $weiboUserInfo['screen_name'];
        $userInfo['intro'] = $weiboUserInfo['description'];

        $_SESSION['user_info'] = $userInfo;

        $this->redirect("/login/doLogin");
    }

    public function doubanAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $douban = Oauth_Adapter::getInstance()->createOauthModel('douban');

        $code = $this->getRequiredParam('code');

        // 如果没有authorizeCode，跳转首页
        if (empty($code)) {
            $this->redirect('/index');

            return ;
        }
        // 设置authorizeCode
        $douban->setAuthorizeCode($code);
        // 通过authorizeCode获取accessToken，至此完成用户授权
        $douban->requestAccessToken();
        
        $userInfoJson = $douban->api('User.me.GET')->makeRequest();

        $doubanUserInfo = json_decode($userInfoJson, true);

        $userInfo = array();
        $userInfo['app_id'] = Conf_Oauth::$appIds['douban'];
        $userInfo['app_uid'] = $doubanUserInfo['id'];
        $userInfo['avatar'] = $doubanUserInfo['avatar'];
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