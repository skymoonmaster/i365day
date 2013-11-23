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
class UserDiaryController extends BasicController {

    public function replaceRelationAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $diaryId = $this->getRequiredParam('diary_id');
        $userId = $this->userInfo['user_id'];
        $relation = $this->getRequiredParam('relation');

        $userDiary = array(
            'diary_id' => $diaryId,
            'user_id' => $userId,
            'relation' => $relation
        );
        $ret = UserDiaryModel::getInstance()->replace($userDiary);
        if($ret){
            $retUpdateFavNum = DiaryModel::getInstance()->addFavNumById($diaryId);
        }
        if (!$ret || !$retUpdateFavNum) {
            throw new Exception_Ajax('replace user diary relation error');
        }
        echo json_encode(array('error_no' => self::ERROR_OK));
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>