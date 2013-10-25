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
class DiaryController extends BasicController {

    public function createAction() {
        $defaultDate = date('Ymd');
        $date = $this->getOptionalParam('date', $defaultDate);

        $this->getView()->assign('date', intval($date));
    }

    public function detailAction() {
        $diaryId = $this->getRequiredParam('diary_id');
        $diaryInfo = DiaryModel::getInstance()->getDiaryById($diaryId);
        $diaryExtInfo = DiaryExtModel::getInstance()->getDiaryExtByDiaryId($diaryId);
        if (!is_array($diaryInfo) || !is_array($diaryExtInfo)) {
            throw new Exception("can not find diary by id $diaryId");
        }
        $diaryInfo['tags'] = json_decode($diaryInfo['tags'], true);
        $diaryInfo = array_merge($diaryInfo, $diaryExtInfo);

        $this->getView()->assign('diary', $diaryInfo);
        $this->getView()->assign('user', $this->userInfo);
    }

    public function doCreateAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $diaryInfo = $this->getDiaryInfo();
        $retUploadFile = FileModel::getInstance()->uploadDiaryPic($diaryInfo['create_time'], 'pic');
        if ($retUploadFile) {
            $picSrc = FileModel::getInstance()->generateSrcForDiaryPic($diaryInfo['create_time'], 'pic');
            $diaryInfo['pic'] = $picSrc;
            $diaryInfo['thumbnail'] = $picSrc;
        }

        $diaryId = DiaryModel::getInstance()->createDiary($diaryInfo);
        if (!$diaryId) {
            throw new Exception('create diray error');
        }
        if (!isset($_FILES['pic'])) {
            throw new Exception_BadInput('pic is empty');
        }
        $this->redirect("/home");
    }

    private function getDiaryInfo() {

        $title = $this->getRequiredParam('title');
        $content = $this->getRequiredParam('content');
        $date = $this->getRequiredParam('date');

        $diaryId = $this->getOptionalParam('diary_id', 0);
        $tags = $this->getOptionalParam('tags', array());
        $private = $this->getOptionalParam('private', 0);
        $picDesc = $this->getOptionalParam('pic_desc', 0);
        if (!$this->userInfo) {
            throw new Exception_Login("invalid user");
        }
        $createTime = time();
        return array(
            'diary_id' => $diaryId,
            'title' => $title,
            'content' => $content,
            'tags' => json_encode($this->filterTags($tags)),
            'visibility' => $private ? 1 : 0,
            'date' => $date,
            'date_ts' => strtotime($date),
            'user_id' => $this->userInfo['user_id'],
            'pic_desc' => $picDesc,
            'create_time' => $createTime
        );
    }

    private function filterTags($tags) {
        $ret = array();
        if (!is_array($tags) || count($tags) == 0) {
            return array();
        }
        foreach ($tags as $tag) {
            if (strlen($tag) == 0) {
                continue;
            }
            $ret[] = $tag;
        }
        return $ret;
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>