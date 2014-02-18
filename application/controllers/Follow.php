<?php

class FollowController extends BasicController {
    public function indexAction() {
        if (!$this->isSelf) {
            $userId = $this->getOptionalParam('p', 0, true);
            $userInfo = UserModel::getInstance()->getUserInfoById($userId);
        } else {
            $userId = $_SESSION['user_id'];
            $userInfo = $this->userInfo;
        }

        $pageNo = $this->getOptionalParam('page', 1);
        if ($pageNo <= 0) {
            $pageNo = 1;
        } elseif ($pageNo > ceil($userInfo['follows'] / AttentionModel::DEFAULT_LIMIT)) {
            $pageNo = ceil($userInfo['follows'] / AttentionModel::DEFAULT_LIMIT);
        }
        $offset = ($pageNo - 1) * AttentionModel::DEFAULT_LIMIT;
        $followUids = AttentionModel::getInstance()->getFollowUids($userId, $offset);

        $page = new Util_Pagination();
        $page['totalItemNum'] = $userInfo['follows'];
        $page['pageSize'] = AttentionModel::DEFAULT_LIMIT;
        $page['pageNum'] = $pageNo;
        $page['totalPageNum'] = ceil($page['totalItemNum'] / $page['pageSize']);

        if (empty($followUids)) {
            $this->getView()->assign('userInfo', $userInfo);
            $this->getView()->assign('page', $page);

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

        $this->getView()->assign('page', $page);
        $this->getView()->assign('userInfo', $userInfo);
        $this->getView()->assign('follows', $follows);
    }
}
