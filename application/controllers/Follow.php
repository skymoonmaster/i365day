<?php

class FollowController extends BasicController {
    public function indexAction() {
        $pageNo = $this->getOptionalParam('page', 1);

        if (!$this->isSelf) {
            $userId = $this->getOptionalParam('p', 0, true);
            $userInfo = UserModel::getInstance()->getUserInfoById($userId);
        } else {
            $userId = $_SESSION['user_id'];
            $userInfo = $this->userInfo;
        }

        $offset = ($pageNo - 1) * AttentionModel::DEFAULT_LIMIT;
        $followUids = AttentionModel::getInstance()->getFollowUids($userId, $offset);

        $follows = array();
        if (!empty($followUids)) {
            $follows = UserModel::getInstance()->getUserInfos($followUids);
        }

        foreach($follows as &$follow) {
            $follow['diary_days'] = DiaryModel::getInstance()->getDiaryAmountByUid($follow['user_id']);
        }

        $this->getView()->assign('userInfo', $userInfo);
        $this->getView()->assign('follows', $follows);
    }
}
