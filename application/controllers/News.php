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
class NewsController extends BasicController {

    public function indexAction() {
        $type = $this->getOptionalParam('type', '');
        $keyword = $this->getAjaxParam('kw', '');
        $order ="ORDER BY date DESC";
        $conditions = array('is_admin' => 1);
        if($type){
            $conditions ['type'] = $type;
        }
        $diaryList = DiaryModel::getInstance()->getDiaryListExtByConditions($conditions, $order);
        $showList = array();
        if($keyword){
            foreach ($diaryList as $diary){
                $typeName = DiaryTypeModel::$diaryTypeIdToName[$diary['type']];
                if(strpos($diary['title'], $keyword) !== false){
                    $showList[] = $diary;
                    continue;
                }
                if(strpos($typeName, $keyword) !== false){
                    $showList[] = $diary;
                    continue;
                }
                if(strpos($diary['content'], $keyword) !== false){
                    $showList[] = $diary;
                    continue;
                }
            }
        }else{
            $showList = $diaryList;
        }
        $this->getView()->assign('diary_list', $showList);
        $this->getView()->assign('kw', $keyword);
        $this->getView()->assign('diary_type_id_to_name', DiaryTypeModel::$diaryTypeIdToName);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>