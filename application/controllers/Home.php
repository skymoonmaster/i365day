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
class HomeController extends BasicController {

    public function indexAction() {
        $isRecordTodayShow = false;
        $inputMonth = $this->getOptionalParam('month', date('Ym'), true);
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id'], true);
        $diaryList = DiaryLogicModel::getInstance()->fillDiaryListForHomepage($inputMonth, $inputUserId);
        if ($inputMonth == date('Ym')) {
            $conditions = array('user_id' => $inputUserId, 'date' => date('Ymd'));
            $diaryInfo = DiaryModel::getInstance()->getSingleDataByConditions($conditions);
            $isRecordTodayShow = $diaryInfo ? false : true;
        }
        $firstDiary = DiaryModel::getInstance()->getFirstDairy($_SESSION['user_id']);
        $userInfo = UserModel::getInstance()->getUserInfoById($inputUserId);

        $this->getView()->assign('user', $userInfo);
        $this->getView()->assign('diary_list', $diaryList);
        $this->getView()->assign('first_date_ts', $firstDiary['date_ts']);
        $this->getView()->assign('current_month', $inputMonth);
        $this->getView()->assign('is_record_today_show', $isRecordTodayShow);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>