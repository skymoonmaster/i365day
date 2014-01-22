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
    
    protected function init() {
        $inputUserId = $this->getRefererOptionalParam('p', $_SESSION['user_id']);
//        $_SESSION['user_id'] = 1;
        if (isset($_SESSION['user_id']) && intval($_SESSION['user_id']) != 0) {
            $this->userInfo = UserModel::getInstance()->getUserInfoById($_SESSION['user_id']);
        }
        $this->getView()->assign('current_user_id', intval($inputUserId));
        $this->getView()->assign('is_self', ($inputUserId == $_SESSION['user_id']));
    }
    public function headerAction() {
        $this->getView()->assign('user', $this->userInfo);
        $this->getView()->assign('current_page', $this->getCurrentPage());
    }

    public function homeAction() {
        $inputUserId = $this->getRefererOptionalParam('p', $_SESSION['user_id'], true);
        $diaryDays = DiaryModel::getInstance()->getDiaryAmountByUid($inputUserId);
        $conditions = array('user_id' => $inputUserId, 'date' => date('Ymd'));
        $diaryInfo = DiaryModel::getInstance()->getSingleDataByConditions($conditions);
        $isRecordTodayShow = $diaryInfo ? false : true;
        $userInfo = UserModel::getInstance()->getUserInfoById($inputUserId);

        $isFollow = false;
        if (!$this->isSelf) {
            $isFollow = AttentionModel::getInstance()->isFollow($_SESSION['user_id'], $inputUserId);
        }

        $this->getView()->assign('user', $userInfo);
        $this->getView()->assign('current_page', $this->getCurrentPage());
        $this->getView()->assign('diary_days', $diaryDays);
        $this->getView()->assign('is_record_today_show', $isRecordTodayShow);
        $this->getView()->assign('is_follow', $isFollow);
    }

    public function pagingAction() {
        
    }

    public function leavingMsgAction() {
        $inputUserId = $this->getRefererOptionalParam('p', $_SESSION['user_id']);
        $leavingMsgList = LeavingMsgModel::getInstance()->getLeavingMsgByOwnerId($inputUserId);
        $leavingMsgAssociate = array();
        if(is_array($leavingMsgList) && count($leavingMsgList) > 0){
            foreach ($leavingMsgList as $leavingMsg){
                $leavingMsgAssociate[$leavingMsg['leaving_msg_id']] = $leavingMsg;
            }
        }
        $this->getView()->assign('leaving_msg_list', $leavingMsgList ? $leavingMsgList : array());
        $this->getView()->assign('leaving_msg_associate', $leavingMsgAssociate);
    }

    public function commentAction() {
        $diaryId = $this->getRequiredParam('diary_id');
        $commentList = CommentModel::getInstance()->getCommentByDiaryId($diaryId);
        $commentAssociate = array();
        if(is_array($commentList) && count($commentList) > 0){
            foreach ($commentList as $comment){
                $commentAssociate[$comment['comment_id']] = $comment;
            }
        }
        $this->getView()->assign('comment_list', $commentList ? $commentList : array());
        $this->getView()->assign('comment_associate', $commentAssociate);
    }

    public function footerAction() {
        
    }

    private function getCurrentPage() {
        $referer = $_SERVER['HTTP_REFERER'];
        $refererInfo = parse_url($referer);
        $pathInfo = explode('/', $refererInfo['path']);
        array_shift($pathInfo);
        return (isset($pathInfo[0]) && $pathInfo[0]) ? $pathInfo[0] : 'home';
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>