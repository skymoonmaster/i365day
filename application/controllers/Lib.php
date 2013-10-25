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
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id']);
        $firstDiary = DiaryModel::getInstance()->getFirstDairy($inputUserId);
        $duration = 1;
        if(isset($firstDiary['date_ts']) && intval($firstDiary['date_ts']) != 0){
            $duration = ceil((time() - $firstDiary['date_ts']) / 86400) + 1;
        }
        $this->getView()->assign('user', $this->userInfo);
        $this->getView()->assign('duration', $duration);
    }
    public function pagingAction() {
        
    }

    public function leavingMsgAction() {
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id']);
        $leavingMsgList = LeavingMsgModel::getInstance()->getLeavingMsgByOwnerId($inputUserId);
        $this->getView()->assign('leaving_msg_list', $leavingMsgList ? $leavingMsgList : array());
    }

    public function commentAction() {
        $diaryId = $this->getRequiredParam('diary_id');
        $commentList = CommentModel::getInstance()->getCommentByDiaryId($diaryId);
        $this->getView()->assign('comment_list', $commentList ? $commentList : array());
    }

    public function footerAction() {
        
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>