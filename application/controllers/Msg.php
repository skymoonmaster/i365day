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
class MsgController extends BasicController {

    public function indexAction() {
        $inputUserId = $this->getOptionalParam('p', $_SESSION['user_id']);
        $userInfo = UserModel::getInstance()->getUserInfoById($inputUserId);
        $this->getView()->assign('user', $userInfo);
    }

    public function readMessageAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        $messageId = $this->getAjaxParam('messageId');
        $diaryId = $this->getAjaxParam('diaryId');
        $messageType = $this->getAjaxParam('messageType');

        MessageModel::getInstance()->readMessage($_SESSION['user_id'], $messageId, $messageType, $diaryId);

        echo Util_Result::success('success');
    }

    public function checkNewMessageAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $amount = MessageModel::getInstance()->getMessageAmount($_SESSION['user_id']);

        echo Util_Result::success('success', $amount);
        return;
    }

    public function getNewMessageAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        $messageInfos = MessageModel::getInstance()->getMessage($_SESSION['user_id']);

        if ($messageInfos === FALSE) {
            return Util_Result::failure('', array());
        }

        echo Util_Result::success('', $messageInfos);
        return;
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>