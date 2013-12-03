<?php


class MessageModel extends BasicModel {
	protected static $instances;
	
	protected $table = 'message';
	
	public static $messageType = array(
		'likeDiary' => 1,
		'DiaryComment' => 2,
		'leavingMessage' => 3
	);
	
	public static $messageReadStatus = array(
		'read' => 1,
		'unread' => 2
	);

	/**
     * @return MessageModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new UserModel();
        }

        return self::$instances;
    }

	public function addMessage($messageType, $senderId, $senderName, $receiverId, $diaryId = 0, $diaryTitle = '') {
		if (!isset($messageType) || empty($messageType) || !in_array($messageType, array_values(self::$messageType))) {
			throw new Exception_BadInput("Invalid message type");
		}	
		
		if ((!isset($senderId) || empty($senderId)) || (!issset($receiverId) || empty($receiverId))) {
			throw new Exception_BadInput("Invalid message sender id or receiver id");
		}
		
		$createTime = $updateTime = time();

		$sql = "INSERT INTO message VALUES (";
		$sql .= "{$messageType}, {$senderId}, {$senderName}, {$receiverId}, ";
		$sql .= self::$messageReadStatus['unread'] . ", ";
		$sql .= "{$createTime}, {$updateTime}, {$diaryId}, {$diaryTitle}, 1) ";		
		$sql .= "ON DUPLICATE KEY UPDATE count = count + 1";

		$this->db->update($sql);
		$messageId = $this->db->getLastInsertID();
		
		$this->setMessageToCache($messageId, $messageType, $senderId, $receiverId, $diaryId);	

		return $messageId;	
	}

	private function setMessageToCache($messageId, $messageType, $senderId, $senderName, $receiverId, $diaryId, $diaryTitle) {
		$mcKey = McKeyModel::getInstance();
		$key = $mcKey->forCompanyInfo('msg', $receiverId, '');

		//消息缓存索引，用于查找出用户一共有多少未读消息
		$memcache = MemcachedModel::getInstance(); 
		$messageCacheKeyIndex = $memcache->get($key);	
		$messageCacheKeyIndex = empty($messageCacheKeyIndex) ? array() : json_decode($messageCacheKeyIndex, true);

		$messageCacheKey = empty($diaryId) ? $messageType : $messageType . '_' . $diaryId;	
		$existedKey = in_array($messageCacheKey, $messageCacheKeyIndex);
		
		$expire = strtotime('+24 week');

		if (!$existedKey) {
			$messageCacheKeyIndex[] = $messageCacheKey;
			
			//将消息内容写进缓存
			$messageContentCacheKey = self::getMessageContentCacheKey();		
			$messageContent = json_encode(array($messageId, $messageType, $senderId, $senderName, $receiverId, $diaryId, $diaryTitle));
			$memcache->set($messageContentCacheKey, $messageContent, $expire);
		
			//将消息缓存key的索引写入缓存
			$memcache->set($messageCacheKeyIndex, json_encode($messageCacheKeyIndex), $expire);			
		}
		
		//消息内计数。譬如：“n人给你留言”、“n人评论了你的日志”等；
		$messageNumCacheKey = self::getMessageNumCacheKey($receiverId, $messageCacheKey);
		if (!$existedKey) {
			$memcache->set($messageNumCacheKey, 1, $expire);
		} else {
			$memcache->increment($messageNumCacheKey);
		}
	} 
	
	private static function getMessageContentCacheKey($receiverId, $messageCacheKey) {
		return McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId . '_' . $messageCacheKey . '_content', '');
	}
	
	private static function getMessageNumCacheKey($receiverId, $messageCacheKey) {
		return McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId . '_' . $messageCacheKey . '_num', '');	
	}

	public function getMessage($receiverId) {
		$messageCache = $this->getMessageFromCache($receiverId);				

		if (!empty($messageCache)) {
			return array('message' => $messageCache, 'messageCount' => count($messageCache));	
		}

		$sql = "SELECT * FROM message WHERE `receiver_id`={$receiverId} AND `is_read`=" . self::$messageReadStatus['unread'];	
		$messages = $this->db->queryAllRows($sql);
		
		return array('message' => $messages, 'messageCount' => count($messages));			
	}

	private function getMessageFromCache($receiverId) {
		$mcKey = McKeyModel::getInstance();
		$key = $mcKey->forCompanyInfo('msg', $receiverId, '');	

		$memcache = MemcachedModel::getInstance(); 
		$messageCacheKeyIndex = $memcache->get($key);		
		
		$messageCacheKeyIndex = json_decode($messageCacheKeyIndex, true);	

		if (empty($messageCacheKeyIndex)) {
			return null;
		}
		
		$messageCacheKeys = array();	

		$messageNumCacheKeys = array();
		$messageContentCacheKeys = array();
		foreach ($messageCacheKeyIndex as $cacheKey) {
			$messageNumCacheKeys[$cacheKey] = self::getMessageNumCacheKey($receiverId, $cacheKey);	
			$messageContentCacheKeys[$cacheKey] = self::getMessageContentCacheKey($receiverId, $cacheKey);
		} 		

		$caches = MemcachedModel::getInstance()->get(array_merge(array_values($messageContentCacheKeys), array_values($messageNumCacheKeys)));
		
		$messages = array();
		foreach ($messageCacheKeys as $cacheKey) {
			if (empty($caches[$messageContentCacheKeys[$cacheKey]])) {
				continue;	
			}
			
			$messageContent = json_decode($caches[$messageContentCacheKeys[$cacheKey]], true);
			
			$messageContent['count'] = empty($caches[$messageNumCacheKeys[$cacheKey]]) ? 1 : $caches[$messageNumCacheKeys[$cacheKey]];

			$messages[] = $messageContent;	
		}

		return $messages;
	}
}