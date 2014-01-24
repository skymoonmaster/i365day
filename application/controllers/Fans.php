<?php


class FansController extends BasicController {
    public function indexAction() {
        $pageNo = $this->getOptionalParam('page', 1);
        $offset = ($pageNo - 1) * AttentionModel::DEFAULT_LIMIT;

        if (!$this->isSelf) {
            $userId = $this->getOptionalParam('p', 0, true);
            $userInfo = UserModel::getInstance()->getUserInfoById($userId);
        } else {
            $userId = $_SESSION['user_id'];
            $userInfo = $this->userInfo;
        }

		$fanUids = AttentionModel::getInstance()->getFanUids($userId, $offset);
        if (empty($fanUids)) {
            $this->getView()->assign('userInfo', $userInfo);

            return ;
        }

        $fans = UserModel::getInstance()->getUserInfos($fanUids);

        //当前登录用户是否关注了
        $isCurrentUserFollows = AttentionModel::getInstance()->isFollows($_SESSION['user_id'], $fanUids);

        foreach($fans as &$fan) {
            $fan['diary_days'] = DiaryModel::getInstance()->getDiaryAmountByUid($fan['user_id']);
            $fan['is_follow'] = $isCurrentUserFollows[$fan['user_id']];
        }

        $this->getView()->assign('userInfo', $userInfo);
        $this->getView()->assign('fans', $fans);
    }

}