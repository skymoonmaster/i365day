<?php
/**
 */
class AttentionController extends BasicController {
    public function addAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        if (empty($this->userInfo)) {
            echo Util_Result::failure('请登录后再试');
            return ;
        }

        $followUid = $this->getAjaxParam('follow_uid');
        if (empty($followUid)) {
            echo Util_Result::failure('缺少参数');
            return ;
        }

        try {
            $result = AttentionModel::getInstance()->addAttention($followUid, $this->userInfo['user_id']);
        } catch(Exception $e) {
            echo Util_Result::failure('关注失败，稍后再试。');
            return ;
        }

        if (!$result) {
            echo Util_Result::failure('关注失败，稍后再试。');
            return ;
        }

        echo Util_Result::success('success');
        return ;
    }

    public function cancelAction() {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        if (empty($this->userInfo)) {
            echo Util_Result::failure('请登录后再试');
            return ;
        }

        $followUid = $this->getAjaxParam('cancel_follow_uid');
        if (empty($followUid)) {
            echo Util_Result::failure('缺少参数');
            return ;
        }

        try {
            $result = AttentionModel::getInstance()->cancelAttention($followUid, $this->userInfo['user_id']);
        } catch(Exception $e) {
            echo Util_Result::failure('取消关注失败，稍后再试。');
            return ;
        }

        if (!$result) {
            echo Util_Result::failure('取消关注失败，稍后再试。');
            return ;
        }

        echo Util_Result::success('success');
        return ;
    }
}
