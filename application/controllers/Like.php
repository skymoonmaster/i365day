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
class LikeController extends BasicController {

    public function indexAction() {
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id']);
        $userDiaryList = UserDiaryModel::getInstance()->getListExtByUserId($inputUserId);
        $this->getView()->assign('user_diary_list', $userDiaryList ? $userDiaryList : array());
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>