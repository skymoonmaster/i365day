<?php
/**
 * 关注关系数量
 *
 * @author zhangyuyi
 */

class AttentionNumModel extends BasicModel {
    protected static $instances;

    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new AttentionNumModel();
        }

        return self::$instances;
    }

    public function increaseFollowNum($uid) {
        $this->increaseFollowNumToCache($uid);
        $this->increaseFollowNumToDb($uid);
    }

    public function increaseFansNum($uid) {
        $this->increaseFansNumToCache($uid);
        $this->increaseFansNumToDb($uid);
    }

    public function decreaseFollowNum($uid) {
        $this->decreaseFollowNumToCache($uid);
        $this->decreaseFollowNumToDb($uid);
    }

    public function decreaseFansNum($uid) {
        $this->decreaseFansNumToCache($uid);
        $this->decreaseFansNumToDb($uid);
    }

    public function getFollowNum($uid) {
        $num = $this->getFollowNumFromCache($uid);
        if ($num === 0) {
            return 0;
        }

        $num = $this->getFollowNumFromDb($uid);

        $this->setFollowNumToCache($uid, $num);

        return $num;
    }

    public function getFansNum($uid) {
        $num = $this->getFansNumFromCache($uid);
        if ($num === 0) {
            return 0;
        }

        $num = $this->getFansNumFromDb($uid);

        $this->setFansNumToCache($uid, $num);

        return $num;
    }

    public function getFansNums(array $uids) {
        if (empty($uids)) {
            throw new Exception_BadInput("Empty params error");
        }

        $multiKey = array();
        foreach ($uids as $uid) {
            $multiKey[$this->getFansNumCacheKey($uid)] = $uid;
        }

        $fanNums = array();

        $results = MemcachedModel::getInstance()->get(array_keys($multiKey));
        foreach ($results as $key => $num) {
            $fanNums[$multiKey[$key]] = $num;
        }

        return $fanNums;
    }

    private function getFollowNumCacheKey($fansUid) {
        return McKeyModel::getInstance()->forCompanyInfo('attention_follow', $fansUid . '_num', '');
    }

    private function setFollowNumToCache($fansUid, $followNum) {
        $key = $this->getFollowNumCacheKey($fansUid);

        return MemcachedModel::getInstance()->set($key, $followNum, 0);
    }

    private function getFollowNumFromCache($fansUid) {
        $key = $this->getFollowNumCacheKey($fansUid);

        return MemcachedModel::getInstance()->get($key);
    }

    private function increaseFollowNumToCache($fansUid) {
        $key = $this->getFollowNumCacheKey($fansUid);

        return MemcachedModel::getInstance()->increase($key);
    }

    private function decreaseFollowNumToCache($fansUid) {
        $key = $this->getFollowNumCacheKey($fansUid);

        return MemcachedModel::getInstance()->decrease($key);
    }

    private function getFollowNumFromDb($uid) {
        $userInfo = UserModel::getInstance()->getSingleDataByConditions(array('user_id' => $uid));

        if (empty($userInfo) || empty($userInfo['follows'])) {
            return 0;
        }

        return $userInfo['follows'];
    }

    private function increaseFollowNumToDb($uid) {
        $sql = "UPDATE user SET `follows`=`follows` + 1  WHERE `user_id` = {$uid}";

        return $this->db->update($sql);
    }

    private function decreaseFollowNumToDb($uid) {
        $sql = "UPDATE user SET `follows`=`follows` - 1  WHERE `user_id` = {$uid} AND `follows` != 0";

        return $this->db->update($sql);
    }

    private function getFansNumCacheKey($followUid) {
        return McKeyModel::getInstance()->forCompanyInfo('attention_fans', $followUid. '_num', '');
    }

    private function getFansNumFromCache($followUid) {
        $key = $this->getFansNumCacheKey($followUid);

        return MemcachedModel::getInstance()->get($key);
    }

    private function setFansNumToCache($uid, $num) {
        $key = $this->getFansNumCacheKey($uid);

        return MemcachedModel::getInstance()->set($key, $num, 0);
    }

    private function increaseFansNumToCache($followUid) {
        $key = $this->getFansNumCacheKey($followUid);

        return MemcachedModel::getInstance()->increase($key);
    }

    private function decreaseFansNumToCache($fansUid) {
        $key = $this->getFansNumCacheKey($fansUid);

        MemcachedModel::getInstance()->decrease($key);
    }

    private function getFansNumFromDb($uid) {
        $userInfo = UserModel::getInstance()->getSingleDataByConditions(array('user_id' => $uid));

        if (empty($userInfo) || empty($userInfo['fans'])) {
            return 0;
        }

        return $userInfo['fans'];
    }

    private function increaseFansNumToDb($uid) {
        $sql = "UPDATE user SET `fans`=`fans` + 1  WHERE `user_id` = {$uid}";

        return $this->db->update($sql);
    }

    private function decreaseFansNumToDb($uid) {
        $sql = "UPDATE user SET `fans`=`fans` - 1  WHERE `user_id` = {$uid} AND `fans` != 0";

        return $this->db->update($sql);
    }
}