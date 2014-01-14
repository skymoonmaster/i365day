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

        $this->getView()->assign('feeds', $feeds);
    }


}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>