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
class MsgController extends BasicController {

    public function indexAction() {
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id']);
        $userInfo = UserModel::getInstance()->getUserInfoById($inputUserId);
        $this->getView()->assign('user', $userInfo);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>