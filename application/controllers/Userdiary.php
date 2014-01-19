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
        $authorId = $this->getRequiredParam('author_id');
        $authorName = $this->getRequiredParam('author_name');
        $diaryTitle = urldecode($this->getRequiredParam('diary_title'));

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

        MessageModel::getInstance()->addMessage(
            MessageModel::$messageType['likeDiary'],
            $this->userInfo['user_id'],
            $this->userInfo['nick_name'],
            $authorId,
            $diaryId,
            $diaryTitle
        );

        $diaryExt = DiaryExtModel::getInstance()->getDiaryExtByDiaryId($diaryId);
        $feedData = array(
            'user_id' => $this->userInfo['user_id'],
            'user_name' => $this->userInfo['nick_name'],
            'type' => FeedModel::$feedType['likeDiary'],
            'content' =>json_encode(
                array(
                    'authorId' => $authorId,
                    'authorName' => $authorName,
                    'title' => $diaryTitle,
                    'content' => mb_substr($diaryExt['content'], 0, 140, 'UTF-8')
                )
            )
        );
        FeedModel::getInstance()->addFeed($this->userInfo['user_id'], $feedData);

        echo json_encode(array('error_no' => self::ERROR_OK));
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>