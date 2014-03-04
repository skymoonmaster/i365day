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
class LikeController extends BasicController {

    public function indexAction() {
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id']);
        $userInfo = UserModel::getInstance()->getUserInfoById($inputUserId);

        $totalItemNum = UserDiaryModel::getInstance()->getLikeDiaryCount($inputUserId);
        $totalPageNum = ceil($totalItemNum / UserDiaryModel::DEFAULT_LIMIT);

        $pageNo = $this->getOptionalParam('page', 1);
        if ($pageNo <= 0) {
            $pageNo = 1;
        } elseif ($pageNo > $totalPageNum) {
            $pageNo = $totalPageNum;
        }
        $offset = ($pageNo - 1) * UserDiaryModel::DEFAULT_LIMIT;
        $userDiaryList = UserDiaryModel::getInstance()->getListExtByUserId($inputUserId, $offset);

        $page = new Util_Pagination();
        $page['totalItemNum'] = $totalItemNum;
        $page['pageSize'] = UserDiaryModel::DEFAULT_LIMIT;
        $page['pageNum'] = $pageNo;
        $page['totalPageNum'] = $totalPageNum;

        $this->getView()->assign('page', $page);
        $this->getView()->assign('user', $userInfo);
        $this->getView()->assign('user_diary_list', $userDiaryList ? $userDiaryList : array());
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>