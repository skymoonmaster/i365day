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

    public function isFollow($followUid, $fansUid) {
        $followUids = $this->getFollowUidsFromCache($fansUid);

        if (in_array($followUid, $followUids)) {
            return TRUE;
        }

        $followNum = $this->getFollowNumFromCache($fansUid);

        if ($followNum <= self::$_cacheLimit) {
            return FALSE;
        }

        $sqlConditions = array(
            'follow_uid' => $followUid,
            'fans_uid' => $fansUid
        );

        $result = $this->getSingleDataByConditions($sqlConditions);
        if (empty($result)) {
            return FALSE;
        }

        return TRUE;
    }

    public function getFollows($uid) {

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

    /**
     * 500
     *
     */
    private function getFansUidsFromDb($uid, $offset, $limit) {
        $sql = "SELECT `fans_uid` FROM attention WHERE `follow_uid`={$uid} ORDER BY `create_time` DESC LIMIT {$offset}, {$limit}";

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
	
	private function setFollowUidsToCache() {

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
