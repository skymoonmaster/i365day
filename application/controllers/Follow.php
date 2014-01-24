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

        if (empty($followUids)) {
            $this->getView()->assign('userInfo', $userInfo);

            return ;
        }

        $follows = UserModel::getInstance()->getUserInfos($followUids);

        if (!$this->isSelf) {
            //当前登录用户是否关注了
            $isCurrentUserFollows = AttentionModel::getInstance()->isFollows($_SESSION['user_id'], $followUids);
        }

        foreach($follows as &$follow) {
            $follow['diary_days'] = DiaryModel::getInstance()->getDiaryAmountByUid($follow['user_id']);
            $follow['is_follow'] = $this->isSelf ? true : $isCurrentUserFollows[$follow['user_id']];
        }

        $this->getView()->assign('isSelf', $this->isSelf);
        $this->getView()->assign('userInfo', $userInfo);
        $this->getView()->assign('follows', $follows);
    }
}
