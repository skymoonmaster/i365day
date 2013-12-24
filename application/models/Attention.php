<?php

/**
 * Description of Attention
 *
 * @author zhangyuyi
 */
class AttentionModel extends BasicModel {
	protected static $instances;

	protected $table = 'attention';

    private static $_cacheLimit = 500;

    //30 * 86400
    private static $_cacheExpire = 2592000;

    const DEFAULT_OFFSET = 0;
    const DEFAULT_LIMIT = 15;

	public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new AttentionModel();
        }

        return self::$instances;
    }

	public function addAttention($followUid, $fansUid) {
        // 1. 清除follow fans的uid缓存
        // 2. 增加follow、fans的计数缓存
		if (empty($followUid) || empty($fansUid)) {
			throw new Exception_BadInput("Empty params error");
		}

		$ret = $this->createWithTimestamp(array('fans_uid' => $fansUid, 'follow_uid' => $followUid));
		if (empty($ret)) {
			throw new Exception("Failed to write attention data to db");
		}

        $this->deleteFollowUidsFromCache($fansUid);
        $this->deleteFansUidsFromCache($followUid);

        $this->increaseFollowNumToCache($fansUid);
        $this->increaseFansNumToCache($followUid);
	}

    public function cancelAttention($followUid, $fansUid) {
        if (empty($followUid) || empty($fansUid)) {
            throw new Exception_BadInput("Empty params error");
        }

        $sql = "DELETE FROM {$this->table} WHERE `follow_uid`={$followUid} AND `fans_uid`={$fansUid}";
        $result = $this->db->update($sql);
        if ($result === FALSE) {
            return FALSE;
        }

        $this->deleteFollowUidsFromCache($fansUid);
        $this->deleteFansUidsFromCache($followUid);

        $this->decreaseFollowNumToCache($fansUid);
        $this->decreaseFansNumToCache($followUid);
    }

    public function isFollows($fansUid, $uids) {
        $followNum = $this->getFollowNumFromCache($fansUid);

        if ($followNum <= self::$_cacheLimit) {
            $followUids = $this->getFollowUidsFromCache($fansUid);

            return array_intersect($uids, $followUids);
        }

        $sql = "SELECT `follow_id` FROM `attention` WHERE `fans_uid`={$fansUid} AND `follow_id` IN (";
        $sql .= implode(',', $uids);
        $sql .= ")";

        $rows = $this->db->queryAllRows($sql);

        $followUids = array();
        foreach ($rows as $row) {
            $followUids[] = $row['follow_id'];
        }

        $isFollows = array();
        foreach ($uids as $uid) {
            $isFollows[$uid] = in_array($uid, $followUids);
        }

        return $isFollows;
    }

    public function isFollow($uid, $followUid) {
        $followUids = $this->getFollowUidsFromCache($uid);

        if (in_array($followUid, $followUids)) {
            return TRUE;
        }

        $followNum = $this->getFollowNumFromCache($uid);

        if ($followNum <= self::$_cacheLimit) {
            return FALSE;
        }

        $sqlConditions = array(
            'follow_uid' => $followUid,
            'fans_uid' => $uid
        );

        $result = $this->getSingleDataByConditions($sqlConditions);
        if (empty($result)) {
            return FALSE;
        }

        return TRUE;
    }

    public function getFollowUids($uid, $offset = self::DEFAULT_OFFSET, $limit = self::DEFAULT_LIMIT) {
        if (empty($uid)) {
            throw new Exception_BadInput("Empty params error");
        }

        if ($offset + $limit <= self::$_cacheLimit) {
            $followUids = $this->getFollowUidsFromCache($uid);

            if (empty($followUids)) {
                $followUids = $this->getFollowUidsFromDb($uid, 0, self::$_cacheLimit);

                if (empty($followUids)) {
                    return array();
                }

                $this->setFollowUidsToCache($uid, $followUids);

                $followUids = array_slice($followUids, $offset, $limit);

                return $followUids;
            }
        }

        $followUids = $this->getFollowUidsFromDb($uid, $offset, $limit);
        if (empty($followUids)) {
            return array();
        }

        return $followUids;
    }

    public function getFansNum(array $uids) {
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

    public function getFanUids($uid, $offset = self::DEFAULT_OFFSET, $limit = self::DEFAULT_LIMIT) {
        if (empty($uid)) {
            throw new Exception_BadInput("Empty params error");
        }

        if ($offset + $limit <= self::$_cacheLimit) {
            $fansUids = $this->getFansUidsFromCache($uid);

            if (empty($fansUids)) {
                $fansUids = $this->getFansUidsFromDb($uid, 0, self::$_cacheLimit);

                if (empty($fansUids)) {
                    return array();
                }

                $this->setFansUidsToCache($uid, $fansUids);

                $fansUids = array_slice($fansUids, $offset, $limit);

                return $fansUids;
            }
        }

        $fansUids = $this->getFansUidsFromDb($uid, $offset, $limit);
        if (empty($fansUids)) {
            return array();
        }

        return $fansUids;
    }

    private function getFollowUidsFromDb($uid, $offset, $limit) {
        $sql = "SELECT `follow_uid` FROM `attention` WHERE `fans_uid`={$uid} ORDER BY `create_time` DESC LIMIT {$offset}, {$limit}";

        $rows = $this->db->queryAllRows($sql);
        if (empty($result)) {
            return array();
        }

        $followUids = array();
        foreach ($rows as $row) {
            $followUids[] = $row['follow_uid'];
        }

        return $followUids;
    }

    /**
     * 500
     *
     */
    private function getFansUidsFromDb($uid, $offset, $limit) {
        $sql = "SELECT `fans_uid` FROM `attention` WHERE `follow_uid`={$uid} ORDER BY `create_time` DESC LIMIT {$offset}, {$limit}";

        $result = $this->db->queryAllRows($sql);
        if (empty($result)) {
            return array();
        }

        $fansUids = array();
        foreach($result as $row) {
            $fansUids[] = $row['fans_uid'];
        }

        return $fansUids;
    }

    private function setFansUidsToCache($followUid, $fansUids) {
        $key = $this->getFansUidsCacheKey($followUid);

        return MemcachedModel::getInstance()->set($key, $fansUids, self::$_cacheExpire);
    }

    private function getFansUidsFromCache($followUid) {
        $key = $this->getFansUidsCacheKey($followUid);

        return MemcachedModel::getInstance()->get($key);
    }

    private function deleteFansUidsFromCache($followUid) {
        $key = $this->deleteFansUidsFromCache($followUid);

        return MemcachedModel::getInstance()->delete($key);
    }

	/**
	 * 获取关注人缓存
	 * 缓存500条
	 */
	private function getFollowUidsFromCache($fansUid) {
        $key = $this->getFollowUidsCacheKey($fansUid);

        MemcachedModel::getInstance()->get($key);
	}

	private function setFollowUidsToCache($fansUid, $followUids) {
        $key = $this->getFollowUidsCacheKey($fansUid);

        return MemcachedModel::getInstance()->set($key, $followUids, self::$_cacheExpire);
	}

    private function deleteFollowUidsFromCache($fansUid) {
        $key = $this->getFollowUidsCacheKey($fansUid);

        return MemcachedModel::getInstance()->delete($key);
    }

	private function getFollowUidsCacheKey($fansUid) {
        return McKeyModel::getInstance()->forCompanyInfo('attention', $fansUid, '');
    }

    private function getFansUidsCacheKey($followUid) {
        return McKeyModel::getInstance()->forCompanyInfo('attention', $followUid, '');
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

    private function getFansNumCacheKey($followUid) {
        return McKeyModel::getInstance()->forCompanyInfo('attention', $followUid. '_num', '');
    }

    private function getFollowNumCacheKey($fansUid) {
        return McKeyModel::getInstance()->forCompanyInfo('attention', $fansUid . '_num', '');
    }

    private function getFansNumFromCache($followUid) {
        $key = $this->getFansNumCacheKey($followUid);

        return MemcachedModel::getInstance()->get($key);
    }

    private function increaseFansNumToCache($followUid) {
        $key = $this->getFansNumCacheKey($followUid);

        return MemcachedModel::getInstance()->increase($key);
    }

    private function decreaseFansNumToCache($fansUid) {
        $key = $this->getFansNumCacheKey($fansUid);

        MemcachedModel::getInstance()->decrease($key);
    }
}
