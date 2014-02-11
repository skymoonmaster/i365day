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
        $dateTimestamp = strtotime(intval($date));
        $firstDiary = DiaryModel::getInstance()->getFirstDairy($_SESSION['user_id']);
        $duration = 1;
        if (isset($firstDiary['date_ts']) && intval($firstDiary['date_ts']) != 0) {
            $duration = ceil(($dateTimestamp - $firstDiary['date_ts']) / 86400) + 1;
        }

        $this->getView()->assign('duration', $duration);
        $this->getView()->assign('date_ts', $dateTimestamp);
    }

    public function detailAction($forEdit = 0) {
        $diaryId = $this->getRequiredParam('diary_id');
        $diaryInfo = DiaryModel::getInstance()->getDiaryById($diaryId);
        $diaryExtInfo = DiaryExtModel::getInstance()->getDiaryExtByDiaryId($diaryId);
        if (!is_array($diaryInfo) || !is_array($diaryExtInfo)) {
            throw new Exception("can not find diary by id $diaryId");
        }
        $diaryInfo['tags'] = json_decode($diaryInfo['tags'], true);
        $diaryInfo = array_merge($diaryInfo, $diaryExtInfo);
        if (!$forEdit) {
            $diaryInfo['content'] = str_replace("\n", "</p><p>", $diaryInfo['content']);
            $diaryInfo['content'] = str_replace(" ", "&nbsp", $diaryInfo['content']);
            $diaryInfo['content'] = str_replace("\t", "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp", $diaryInfo['content']);
        }
        $isRelated = UserDiaryModel::getInstance()->isRelated($_SESSION['user_id'], $diaryId);
        $diaryDays = DiaryModel::getInstance()->getDiaryAmountByUid($_SESSION['user_id']);
        $this->getView()->assign('diary', $diaryInfo);
        $this->getView()->assign('user', $this->userInfo);
        $this->getView()->assign('diary_days', $diaryDays);
        $this->getView()->assign('is_related', $isRelated);
    }

    public function editAction() {
        $this->detailAction(1);
    }

    public function doCreateAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $diaryInfo = $this->getDiaryInfo();
        $picUrl = FileModel::getInstance()->uploadDiaryPic($diaryInfo['create_time'], 'pic');
        if ($picUrl) {
            $diaryInfo['pic'] = $picUrl;
            $diaryInfo['thumbnail'] = $picUrl;
        }
        $diaryId = DiaryModel::getInstance()->createDiary($diaryInfo);
        if (!$diaryId) {
            throw new Exception('create diray error');
        }
        if (!isset($_FILES['pic'])) {
            throw new Exception_BadInput('pic is empty');
        }

        $feedData = array(
            'user_id' => $this->userInfo['user_id'],
            'user_name' => $this->userInfo['nick_name'],
            'type' => FeedModel::$feedType['diary'],
            'content' => json_encode(
                    array(
                        'diary_id' => $diaryId,
                        'title' => $diaryInfo['title'],
                        'content' => mb_substr($diaryInfo['content'], 0, 220, 'UTF-8')
                    )
            )
        );
        FeedModel::getInstance()->addFeed($this->userInfo['user_id'], $feedData);

        $this->redirect("/diary/detail/diary_id/" . $diaryId);
    }

    public function doEditAction() {
        $diaryInfo = $this->getDiaryInfo();
        $diaryId = $this->getOptionalParam('diary_id', 0);
        if (isset($_FILES['pic']['size']) && $_FILES['pic']['size'] != 0) {
            $retUploadFile = FileModel::getInstance()->uploadDiaryPic($diaryInfo['create_time'], 'pic');
            if ($retUploadFile) {
                $picSrc = FileModel::getInstance()->generateSrcForDiaryPic($diaryInfo['create_time'], 'pic');
                $diaryInfo['pic'] = $picSrc;
                $diaryInfo['thumbnail'] = $picSrc;
            }
        }
        DiaryModel::getInstance()->updateDiary($diaryInfo);
        $this->redirect("/diary/detail/diary_id/" . $diaryId);
    }

    public function delAction() {
        $diaryId = $this->getRequiredParam('diary_id');
        DiaryModel::getInstance()->delDiaryById($diaryId);
        $this->redirect("/home");
    }

    private function getDiaryInfo() {

        $content = $this->getRequiredParam('content');
        $date = $this->getRequiredParam('date');
        $title = $this->getOptionalParam('title', $this->defaultTitle(strtotime($date)));
        $diaryType = $this->getOptionalParam('type', 0);
        $diaryId = $this->getOptionalParam('diary_id', 0);
        $diaryExtId = $this->getOptionalParam('diary_ext_id', 0);
        $tags = $this->getOptionalParam('tags', array());
        $private = $this->getOptionalParam('private', 0);
        $picDesc = $this->getOptionalParam('pic_desc', 0);
        if (!$this->userInfo) {
            throw new Exception_Login("invalid user");
        }
        $createTime = time();
        return array(
            'diary_id' => $diaryId,
            'diary_ext_id' => $diaryExtId,
            'title' => $title,
            'type' => $diaryType,
            'content' => $content,
            'tags' => json_encode($this->filterTags($tags)),
            'visibility' => $private ? 1 : 0,
            'date' => $date,
            'date_ts' => strtotime($date),
            'user_id' => $this->userInfo['user_id'],
            'pic_desc' => $picDesc,
            'is_admin' => $diaryType ? 1 : 0,
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
    
    private function defaultTitle($dateTS){
        $firstDateTS = strtotime(date('Y', $dateTS) . '-01-01');
        $days = intval(($dateTS - $firstDateTS) / 86400 + 1);
        return $days . '/365@' . date('Y');
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>