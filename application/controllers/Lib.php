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
    
    public function headerAction() {
        $this->getView()->assign('user', $this->userInfo);
    }

    public function homeAction() {
        $firstDiary = DiaryModel::getInstance()->getFirstDairy($_SESSION['user_id']);
        $duration = ceil((time() - $firstDiary['create_time']) / 86400); 
        $this->getView()->assign('user', $this->userInfo);
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
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>