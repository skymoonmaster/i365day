<?php


class FansController extends BasicController {
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
        } elseif ($pageNo > ceil($userInfo['fans'] / AttentionModel::DEFAULT_LIMIT)) {
            $pageNo = ceil($userInfo['fans'] / AttentionModel::DEFAULT_LIMIT);
        }
        $offset = ($pageNo - 1) * AttentionModel::DEFAULT_LIMIT;
		$fanUids = AttentionModel::getInstance()->getFanUids($userId, $offset);

        $page = new Util_Pagination();
        $page['totalItemNum'] = $userInfo['fans'];
        $page['pageSize'] = AttentionModel::DEFAULT_LIMIT;
        $page['pageNum'] = $pageNo;
        $page['totalPageNum'] = ceil($page['totalItemNum'] / $page['pageSize']);

        if (empty($fanUids)) {
            $this->getView()->assign('userInfo', $userInfo);
            $this->getView()->assign('page', $page);

            return ;
        }

        $fans = UserModel::getInstance()->getUserInfos($fanUids);

        //当前登录用户是否关注了
        $isCurrentUserFollows = AttentionModel::getInstance()->isFollows($_SESSION['user_id'], $fanUids);

        foreach($fans as &$fan) {
            $fan['diary_days'] = DiaryModel::getInstance()->getDiaryAmountByUid($fan['user_id']);
            $fan['is_follow'] = $isCurrentUserFollows[$fan['user_id']];
        }

        $this->getView()->assign('page', $page);
        $this->getView()->assign('userInfo', $userInfo);
        $this->getView()->assign('fans', $fans);
    }

}