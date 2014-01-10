<?php


class FansController extends BasicController {
    public function indexAction() {
        $pageNo = $this->getRequiredParam('page');

        $offset = ($pageNo - 1) * AttentionModel::DEFAULT_LIMIT;
		$fanUids = AttentionModel::getInstance()->getFanUids($_SESSION['user_id'], $offset);

        $fans = array();
        if (!empty($fanUids)) {
            $fans = UserModel::getInstance()->getUserInfos($fanUids);
        }

        $fansNum = AttentionNumModel::getInstance()->getFansNums($fanUids);

        foreach($fans as &$fan) {
            $fan['fans_num'] = $fansNum[$fan['user_id']];
        }

        $this->getView()->assign('fans', $fans);
    }

}