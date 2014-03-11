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
class FriendController extends BasicController {

    public function indexAction() {
        $feeds = FeedModel::getInstance()->getFeeds($this->userInfo['user_id'], 0, 500);

        $followUids = AttentionModel::getInstance()->getFollowUids($_SESSION['user_id'], 0, 9);

        if (!empty($followUids)) {
            $follows = UserModel::getInstance()->getUserInfos($followUids);

            $this->getView()->assign('follows', $follows);
        }

        $this->getView()->assign('userInfo', $this->userInfo);
        $this->getView()->assign('feeds', $feeds);
    }


}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>