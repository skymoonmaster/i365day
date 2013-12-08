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
	
	public function checkNewMessageAction() {
		Yaf_Dispatcher::getInstance()->autoRender(false);	
//		$amount = MessageModel::getInstance()->getMessageAmount($receiverId);
		$result = new Util_Result(0, 'msg', 1000);
		
		echo $result;
	}
	
	public function getNewMessageAction() {
		Yaf_Dispatcher::getInstance()->autoRender(false);
//		$messageInfos = MessageModel::getInstance()->getMessage($receiverId);
//		
//		if ($messageInfos === FALSE) {
//			return Util_Result::failure($code, 'success', array());
//		}
		
		//$messageId, $messageType, $senderId, $senderName, $receiverId, $diaryId, $diaryTitle
		$messageInfos = array(
			'message' => array(
				array(
					'messageId' => 1,
					'messageType' => 2,
					'senderName' => 'zhangyuyi',
					'diaryTitle' => '这是谁的小孩子',
					'count' => 5,
				),
				array(
					'messageId' => 2,
					'messageType' => 3,
					'senderName' => 'zhyy',
					'diaryTitle' => '谁家的小睡',
					'count' => 4,		
				),
				array(
					'messageId' => 2,
					'messageType' => 1,
					'senderName' => 'wushuang',
					'diaryTitle' => '谁家的小睡',
					'count' => 3,		
				),
			),
			'messageCount' => 50
		);	
		
		echo Util_Result::success(0, '', $messageInfos);
	}
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>