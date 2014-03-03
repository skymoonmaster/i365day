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
class IndexController extends BasicController {
    const COOKIE_STRING = 'i365daysh+4kFAADzgC+nx';

    const LOGIN_SUCCESS_COOKIE_NAME = 'i002';

    public function indexAction() {
        //TODO 是否携带邀请码
        list($result, $userId) = UserAuthModel::getInstance()->isLogin();

        if ($result) {
            $this->redirect("/home");

            return ;
        }
    }


}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>