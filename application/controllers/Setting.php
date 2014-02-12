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
class SettingController extends BasicController {

    public function indexAction() {
        $this->getView()->assign('user', $this->userInfo);
        $this->getView()->assign('time_zones', $this->timezones);
    }

    public function doSaveAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $userInfo = $this->getUserInfo();
        UserModel::getInstance()->update($userInfo);
        $this->redirect("/setting");
    }

    public function doUpdateIntroAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $userId = $this->getRequiredParam('user_id');
        $intro = $this->getRequiredParam('intro');
        $ret = UserModel::getInstance()->update(array('user_id' => intval($userId), 'intro' => $intro));
        echo json_encode(array('errno' => $ret));
    }

    private function getUserInfo() {
        return array(
            'user_id' => $this->getRequiredParam('user_id'),
            'nick_name' => $this->getRequiredParam('nick_name'),
            'intro' => $this->getRequiredParam('intro'),
            'city' => $this->getRequiredParam('city'),
            'camera' => $this->getRequiredParam('camera'),
            'time_zone' => $this->getRequiredParam('time_zone')
        );
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>