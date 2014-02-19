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
        $this->getView()->assign('width_avatar', 1);
        $this->getView()->assign('time_zones', $this->timezones);
    }

    public function doSaveAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $avatar = $this->processAvatar();
        $userInfo = $this->getUserInfo();
        $userInfo['avatar'] = $avatar;
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

    private function processAvatar() {

        $src = dirname(__FILE__) . '/../../statics/images/pool.jpg';
        $imgSize = getimagesize($src);
        $origWidth = $imgSize[0];
        $origHeight = $imgSize[1];
        $resizeRate = round(max($origWidth/220, $origHeight/220), 2);
        
        $imgRes = imagecreatefromjpeg($src);
        $dstRes = ImageCreateTrueColor(AVATAR_WIDTH, AVATAR_HEIGHT);

        imagecopyresampled($dstRes, $imgRes, 0, 0, $_POST['x'] * $resizeRate, $_POST['y'] * $resizeRate, 
                AVATAR_WIDTH, AVATAR_HEIGHT, $_POST['w'] * $resizeRate, $_POST['h'] * $resizeRate);
        $avatarFilename = FileModel::getInstance()->generateFilenameForAvatar($src);
        imagejpeg($dstRes, $avatarFilename, AVATAR_QUALITY);
        
        $ret = Storage_QiNiuCloudStorage::upload(md5($_SESSION['user_id'] . time()), $avatarFilename);
        if (empty($ret)) {
            throw new Exception("file upload failed");
        }
        return Storage_QiNiuCloudStorage::getPicUrl($ret['key']);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>