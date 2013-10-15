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
class LibController extends BasicController {
    
    private $userInfo = array();
    public function headerAction() {
        $userInfo = $this->getUserInfo();
        $this->getView()->assign('user', $userInfo);
    }

    public function homeAction() {
        $userInfo = $this->getUserInfo();
        $firstDiary = DiaryModel::getInstance()->getFirstDairy($_SESSION['user_id']);
        $duration = ceil((time() - $firstDiary['create_time']) / 86400); 
        $this->getView()->assign('user', $userInfo);
        $this->getView()->assign('duration', $duration);
    }

    public function likeAction() {
        
    }

    public function pagingAction() {
        
    }

    public function commentAction() {
        
    }

    public function footerAction() {
        
    }
    
    private function getUserInfo(){
        if(!isset($_SESSION['user_id']) || intval($_SESSION['user_id']) == 0){
            throw new Exception_Login('please login first');
        }
        if(!$this->userInfo){
            $this->userInfo = UserModel::getInstance()->getUserInfoById($_SESSION['user_id']);
        }
        return $this->userInfo;
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>