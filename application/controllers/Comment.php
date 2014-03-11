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
class CommentController extends BasicController {

    public function doCreateAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $diaryId = $this->getRequiredParam('diary_id');
        $followId = $this->getRequiredParam('follow_id');
        $content = $this->getRequiredParam('content');
        $author = $this->getRequiredParam('user_id');
        $diaryTitle = $this->getRequiredParam('diary_title');
        $comment = array(
            'diary_id' => intval($diaryId),
            'follow_id' => intval($followId),
            'content' => $content,
            'vistor_id' => $this->userInfo['user_id'],
            'vistor_name' => $this->userInfo['nick_name']
        );
        $ret = CommentModel::getInstance()->createWithTimestamp($comment);
        if ($ret) {
            $retUpdateCommentNum = DiaryModel::getInstance()->addCommentNumById($diaryId);
        }
        if (!$ret || !$retUpdateCommentNum) {
            throw new Exception('create comment error');
        }

//        if ($author != $this->userInfo['user_id']) {
        //send message
        MessageModel::getInstance()->addMessage(
                MessageModel::$messageType['DiaryComment'], $this->userInfo['user_id'], $this->userInfo['nick_name'], $author, $diaryId, $diaryTitle);
//        }

        $this->redirect("/diary/detail/diary_id/" . intval($diaryId));
    }

    public function delAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $commentId = $this->getRequiredParam('comment_id');
        $diaryId = $this->getRequiredParam('diary_id');
        $comment = array(
            'comment_id' => $commentId,
            'status' => CommentModel::$statusDel
        );
        $ret = CommentModel::getInstance()->update($comment);
        if (!$ret) {
            throw new Exception('del comment error');
        }
        $this->redirect("/diary/detail/diary_id/" . intval($diaryId));
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>