<?php

class FollowController extends BasicController {
    public function indexAction() {
        $pageNo = $this->getRequiredParam('page');

        $offset = ($pageNo - 1) * AttentionModel::DEFAULT_LIMIT;
        $followUids = AttentionModel::getInstance()->getFollowUids($_SESSION['user_id'], $offset);

        $follows = array();
        if (!empty($followUids)) {
            $follows = UserModel::getInstance()->getUserInfos($followUids);
        }

        $followNum = AttentionModel::getInstance()->getFansNum($followUids);

        foreach($follows as &$follow) {
            $follow['fans_num'] = $followNum[$follow['user_id']];
        }

        $this->getView()->assign('follows', $follow);
    }
}
