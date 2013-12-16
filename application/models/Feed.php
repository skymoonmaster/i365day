<?php

Class FeedModel extends BasicModel {

	protected static $instances;
	
	protected $table = 'half_year_feed';
	
	public static $feedType = array(
		'diary' => 1,	
		'likeDiary' => 2,
		'beFriend' => 3
	);
	
	public static $feedStatus = array (
		'normal' => 1,
		'userDeleted' => 2,
		'sysDeleted' => 3
	);
	
	const FEED_CACHE_KEY = 'user_feeds_';	
	
	const FEED_CACHE_ITEM_NUMS = 500;
	
	const DEFAULT_USER_LATEST_QUERY_TIME = "half year ago";
	/**
     * @return FeedModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new FeedModel();
        }

        return self::$instances;
    }

	public function addFeed($userId, $feedData = array()) {
		if (empty($userId) || !is_array($feedData)) {
			throw new Exception_BadInput("Empty params error");
		}
	
		if (!isset($feedData['type']) || empty($feedData['type']) || !in_array($feedData['type'], array_values(self::$feedType))) {
			throw new Exception_BadInput("Invalid feed type");
		}

		if (!isset($feedData['content']) || empty($feedData['content'])) {
			throw new Exception_BadInput("Invalid feed content");
		}
		
		$feedData['status'] = self::$feedStatus['normal']; 

		$feedId = FeedContentModel::getInstance()->createWithTimestamp($feedData);			
		if (empty($feedId)) {
			throw new Exception("Failed to write feed content data to db");
		}

		$ret = $this->createWithTimestamp(array($feedId, $userId));	
		if (empty($ret)) {
			throw new Exception("Failed to write feed data to db");
		}
		
		$this->_setUserLatestUpdateTimeToCache($userId);

		return true;
	}
	
	/**
	 * 从缓存中读取用户feed
	 * 
	 * @param type $userId
	 * @return type
	 */
	private function _getFeedInfosFromCache($userId) {
		$key = McKeyModel::getInstance()->forCompanyInfo('user_home_feed_', $userId, '');
		MemcachedModel::getInstance()->get($key);
	}
	
	/**
	 * 将用户feed写入缓存
	 * 
	 * @param type $userId
	 * @param type $feedInfos
	 */
	private function _setFeedInfosToCache($userId, $feedInfos) {
		$key = McKeyModel::getInstance()->forCompanyInfo('user_home_feed_', $userId, '');
		MemcachedModel::getInstance()->set($key, $feedInfos, 0);	
	}

	private function _getUserLatestQueryTimeFromCache($userId) {
		$key = McKeyModel::getInstance()->forCompanyInfo('user_latest_query_time_', $userId, '');
		return MemcachedModel::getInstance()->get($key);
	}
	
	/**
	 * 
	 * @param type $userId
	 * @param type $currentTime
	 */
	private function _setUserLatestQueryTimeToCache($userId, $currentTime) {
		$key = McKeyModel::getInstance()->forCompanyInfo('user_latest_query_time_', $userId, '');		
		MemcachedModel::getInstance()->set($key, $currentTime, 0);
	}	

	/**
	 * 获取最近更新的关注用户id
	 * 
	 * @param type $userId
	 * @param type $userLatestQueryTime
	 */	
	private function _getLatestUpdateFollowingUserIdsFromCache($userId, $userLatestQueryTime) {
		//1 首先获取关注人列表	
		//2 在缓存中遍历
		//
		$latestUpdateFollowingUserIds = array();	
		$keys = array();
		foreach ($followingUserIds as $uid) {
			$keys[$uid] = McKeyModel::getInstance()->forCompanyInfo('user_latest_update_time_', $uid, '');	
		}

		$caches = MemcachedModel::getInstance()->get($keys);	

		foreach ($followingUserIds as $uid) {
			$key = $keys[$uid];

			if (!isset($caches[$key])) {
				continue;
			}
			
			$latestUpdateFollowingUserIds[$uid] = $caches[$key];
		}

		return $latestUpdateFollowingUserIds;
	}
	
	private function _setUserLatestUpdateTimeToCache($userId) {
		$key = McKeyModel::getInstance()->forCompanyInfo('user_latest_update_time_', $userId, '');
		
		$updateTime = time();	
		$lifeTime = $updateTime + 86400 * 30 * 6; 

		MemcachedModel::getInstance()->set($key, time(), $lifeTime);	
	}

	private function _getLatestFeedInfosFromDb($userId) {
		$userLatestQueryTime = $this->_getUserLatestQueryTimeFromCache($userId);	
		if (empty($userLatestQueryTime)) {
			$userLatestQueryTime = strtotime(self::DEFAULT_USER_LATEST_QUERY_TIME);
		}

		$latestUpdateFollowUserIds = array_keys($this->_getLatestUpdateFollowingUserIdsFromCache($userId, $userLatestQueryTime));
		
		$sql = 'SELECT * FROM `' . $this->table . '` WHERE `user_id` IN (';
		$separator = '';
		foreach ($latestUpdateFollowUserIds as $id) {
			$sql .= $separator . $id;
			$separator = ',';
		}	

		$sql .= ') AND `create_time` > ' . $userLatestQueryTime;
		$sql .= ' ORDER BY `create_time` DESC limit ' . self::FEED_CACHE_ITEM_NUMS;
		
		$result = $this->db->queryAllRows($sql);	
		if (empty($result)) {
			return array();
		}

		return $result;
	}

	public function getFeeds($userId, $offset, $limit) {
		$currentTime = time();	

		if (empty($userId))	{
			throw new Exception_BadInput("Empty params error");
		}
		
		/**
		 * $feedInfos = array($feedInfo1, $feedInfo2, $feedInfo3 ... $feedInfoN);
		 * $feedInfo = array($feedId, $userId, $createTime, $updateTime);
		 */
		$feedInfos = $this->_getFeedInfosFromCache($userId);
			
		//当用户查看feed第一页数据时，更新feed缓存
		if ($offset == 0) {
			$feedInfosFromDb = $this->_getLatestFeedInfosFromDb($userId);	
			
			$feedInfos = array_merge($feedInfosFromDb, $feedInfos);	

			$this->_setFeedInfosToCache($userId, $feedInfos);	
			$this->_setUserLatestQueryTimeToCache($userId, $currentTime);
		}
		
		if (empty($feedInfos)) {
			return array();			
		}	
		
		$feedInfos = array_slice($feedInfos, $offset, $limit);

		$feeds = FeedContentModel::getInstance()->getFeedContent($feedInfos);
		
		return $feeds;
	}	
}

?>
