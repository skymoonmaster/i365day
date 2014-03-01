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

    public function doUploadAvatarAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $fname = $_FILES ['avatar'] ['name'];
        $pathInfo = pathinfo($fname);
        $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
        $targetFilename = FileModel::getInstance()->generateFilenameForAvatar($extension);
        $picUrl = FileModel::getInstance()->uploadAvatar('avatar', $targetFilename);
        $showAvatarSize = $this->getShowAvatarSize($targetFilename);

        $result = array('code' => 1, 'pic_url' => $picUrl, 'extension' => $extension, 'time' => time());
        echo json_encode(array_merge($result, $showAvatarSize));
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
        $extension = $this->getRequiredParam('extension');
        $src = FileModel::getInstance()->generateFilenameForAvatar($extension);
        $showAvatarSize = $this->getShowAvatarSize($src);
        $resizeRate = $showAvatarSize['rate'] ? $showAvatarSize['rate'] : 1;
        
        if($extension == 'jpg'){
            $imgRes = imagecreatefromjpeg($src);
        }else if($extension == 'gif'){
            $imgRes = imagecreatefromgif($src);
        }else{
            $imgRes = imagecreatefrompng($src);
        }
        
        $dstRes = ImageCreateTrueColor(AVATAR_WIDTH, AVATAR_HEIGHT);

        imagecopyresampled($dstRes, $imgRes, 0, 0, $_POST['x'] * $resizeRate, $_POST['y'] * $resizeRate, AVATAR_WIDTH, AVATAR_HEIGHT, $_POST['w'] * $resizeRate, $_POST['h'] * $resizeRate);
        $avatarFilename = FileModel::getInstance()->generateFilenameForAvatar($extension);
        
        if($extension == 'jpg'){
            imagejpeg($dstRes, $avatarFilename, AVATAR_QUALITY);
        }else if($extension == 'gif'){
            imagegif($dstRes, $avatarFilename);
        }else{
            imagepng($dstRes, $avatarFilename);
        }
        $ret = Storage_QiNiuCloudStorage::upload(md5($_SESSION['user_id'] . time()), $avatarFilename);
        if (empty($ret)) {
            throw new Exception("file upload failed");
        }
        return Storage_QiNiuCloudStorage::getPicUrl($ret['key']);
    }

    private function getShowAvatarSize($avatarSrc) {
        $imgSize = getimagesize($avatarSrc);
        $origWidth = $imgSize[0];
        $origHeight = $imgSize[1];
        if ($origWidth < AVATAR_CROP_WIDTH && $origHeight < AVATAR_CROP_HEIGHT) {
            return array('width' => $origWidth, 'height' => $origHeight, 'rate' => 1);
        }
        $widthRate = round($origWidth / 220, 3);
        $heightRate = round($origHeight / 220, 3);
        if ($widthRate >= $heightRate) {
            return array('width' => intval($origWidth / $widthRate), 'height' => intval($origHeight / $widthRate), 'rate' => $widthRate);
        }
        return array('width' => intval($origWidth / $heightRate), 'height' => intval($origHeight / $heightRate), 'rate' => $heightRate);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>