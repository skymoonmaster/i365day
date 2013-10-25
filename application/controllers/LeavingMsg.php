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
class LeavingMsgController extends BasicController {

    public function doCreateAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $followId = $this->getRequiredParam('follow_id');
        $hostId = $this->getRequiredParam('host_id');
        $content = $this->getRequiredParam('content');
        $comment = array(
            'follow_id' => intval($followId),
            'host_id' => intval($hostId),
            'content' => $content,
            'vistor_id' => $this->userInfo['user_id'],
            'vistor_name' => $this->userInfo['nick_name']
        );
        $ret = LeavingMsgModel::getInstance()->createWithTimestamp($comment);
        if (!$ret) {
            throw new Exception('create leaving msg error');
        }
        $this->redirect("/coinfo/index");
    }
    public function doDelAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $leavingMsgId = $this->getRequiredParam('leaving_msg_id');
        $leavingMsg = array(
            'leaving_msg_id' => $leavingMsgId,
            'status' => CommentModel::$statusDel
        );
        $ret = LeavingMsgModel::getInstance()->update($leavingMsg);
        if (!$ret) {
            throw new Exception('del leaving msg error');
        }
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>