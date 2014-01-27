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
       $order = "ORDER BY date DESC";
       $diaryList = DiaryModel::getInstance()->getDataListByConditions(array('is_admin' => 1), $order);
       $this->getView()->assign('diary_list', $diaryList);
       $this->getView()->assign('diary_type_id_to_name', DiaryTypeModel::$diaryTypeIdToName);
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>