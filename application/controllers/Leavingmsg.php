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
class LeavingmsgController extends BasicController {
    public function indexAction() {
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id']);
        $userInfo = UserModel::getInstance()->getUserInfoById($inputUserId);

        $totalItemNum = LeavingMsgModel::getInstance()->getLeavingMsgCount($inputUserId);
        $totalPageNum = ceil($totalItemNum / LeavingMsgModel::DEFAULT_LIMIT);

        $pageNo = $this->getOptionalParam('page', 1);
        if ($pageNo <= 0) {
            $pageNo = 1;
        } elseif ($pageNo > $totalPageNum) {
            $pageNo = $totalPageNum;
        }
        $offset = ($pageNo - 1) * LeavingMsgModel::DEFAULT_LIMIT;

        $inputUserId = $this->getRefererOptionalParam('p', $_SESSION['user_id']);
        $leavingMsgList = LeavingMsgModel::getInstance()->getLeavingMsgByOwnerId($inputUserId, $offset);
        $leavingMsgAssociate = array();
        if(is_array($leavingMsgList) && count($leavingMsgList) > 0){
            foreach ($leavingMsgList as $leavingMsg){
                $leavingMsgAssociate[$leavingMsg['leaving_msg_id']] = $leavingMsg;
            }
        }

        $page = new Util_Pagination();
        $page['totalItemNum'] = $totalItemNum;
        $page['pageSize'] = LeavingMsgModel::DEFAULT_LIMIT;
        $page['pageNum'] = $pageNo;
        $page['totalPageNum'] = $totalPageNum;

        $this->getView()->assign('page', $page);
        $this->getView()->assign('leaving_msg_list', $leavingMsgList ? $leavingMsgList : array());
        $this->getView()->assign('leaving_msg_associate', $leavingMsgAssociate);
        $this->getView()->assign('user', $userInfo);
    }

    public function doCreateAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $followId = $this->getRequiredParam('follow_id');
        $hostId = $this->getRequiredParam('host_id');
        $content = $this->getRequiredParam('content');
        $comment = array(
            'follow_id' => intval($followId),
            'host_id' => intval($hostId),
            'content' => $content,
            'vistor_id' => $this->userInfo['user_id'],
            'vistor_name' => $this->userInfo['nick_name']
        );
        $ret = LeavingMsgModel::getInstance()->createWithTimestamp($comment);
        if (!$ret) {
            throw new Exception('create leaving msg error');
        }
        MessageModel::getInstance()->addMessage(
            MessageModel::$messageType['leavingMessage'],
            $this->userInfo['user_id'],
            $this->userInfo['nick_name'],
            $hostId
        );
        $redirectUri = ($hostId != $_SESSION['user_id']) ? '/leavingmsg/index/p/' . intval($hostId) : '/leavingmsg';
        $this->redirect($redirectUri);
    }
    public function delAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $leavingMsgId = $this->getRequiredParam('leaving_msg_id');
        $leavingMsg = array(
            'leaving_msg_id' => $leavingMsgId,
            'status' => CommentModel::$statusDel
        );
        $ret = LeavingMsgModel::getInstance()->update($leavingMsg);
        if (!$ret) {
            throw new Exception('del leaving leavingmsg error');
        }
        $this->redirect('/leavingmsg');
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>