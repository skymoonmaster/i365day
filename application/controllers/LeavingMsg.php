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
        $diaryId = $this->getRequiredParam('diary_id');
        $content = $this->getRequiredParam('content');
        $comment = array(
            'diary_id' => intval($diaryId),
            'content' => $content,
            'user_id' => $_SESSION['user_id']
        );
        $ret = CommentModel::getInstance()->createWithTimestamp($comment);
        if (!$ret) {
            throw new Exception('create comment error');
        }
    }
    public function doDelAction() {
        $commentId = $this->getRequiredParam('leaving_msg_id');
        $comment = array(
            'leaving_msg_id' => $commentId,
            'status' => CommentModel::$statusDel
        );
        $ret = CommentModel::getInstance()->update($comment);
        if (!$ret) {
            throw new Exception('del comment error');
        }
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>