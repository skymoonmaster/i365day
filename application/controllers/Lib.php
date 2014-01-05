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
        $firstDiary = DiaryModel::getInstance()->getFirstDairy($inputUserId);
        $duration = 1;
        if (isset($firstDiary['date_ts']) && intval($firstDiary['date_ts']) != 0) {
            $duration = ceil((time() - $firstDiary['date_ts']) / 86400) + 1;
        }
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
        $this->getView()->assign('duration', $duration);
        $this->getView()->assign('is_record_today_show', $isRecordTodayShow);
        $this->getView()->assign('is_follow', $isFollow);
    }

    public function pagingAction() {
        
    }

    public function leavingMsgAction() {
        $inputUserId = $this->getRefererOptionalParam('p', $_SESSION['user_id']);
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