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
        
    }

    public function doCreateAction() {
        $diaryInfo = $this->getDiaryInfo();
        $retUploadFile = FileModel::getInstance()->uploadDiaryPic($diaryInfo['create_time'], 'pic');
        $diaryInfo['pic'] = $retUploadFile;
        
        var_dump($diaryInfo);exit;
        $diaryId = DiaryModel::getInstance()->createDiary($diaryInfo);
        if (!$diaryId) {
            throw new Exception('create diray error');
        }
        if (!isset($_FILES['pic'])) {
            throw new Exception_BadInput('pic is empty');
        }
    }

    private function getDiaryInfo() {

        $title = $this->getRequiredParam('title');
        $content = $this->getRequiredParam('content');
        $date = $this->getRequiredParam('date');

        $diaryId = $this->getOptionalParam('diary_id', 0);
        $tags = $this->getOptionalParam('tags', array());
        $private = $this->getOptionalParam('private', 0);
        $picDesc = $this->getOptionalParam('pic_desc', 0);
        $userInfo = UserModel::getInstance()->getUserInfoById($_SESSION['user_id']);
        if (!$userInfo) {
            throw new Exception_Login("invalid user");
        }
        return array(
            'diary_id' => $diaryId,
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'tags' => json_encode($this->filterTags($tags)),
            'visibility' => $private ? 1 : 0,
            'pic_desc' => $picDesc,
            'create_time' => time()
        );
    }
    
    private function filterTags($tags){
        $ret = array();
        if(!is_array($tags) || count($tags) == 0){
            return array();
        }
        foreach ($tags as $key => $tag){
            if(strlen($tag) == 0){
                continue;
            }
            $ret[] = $tag;
        }
        return $ret;
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>