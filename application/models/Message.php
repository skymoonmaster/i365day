<?php


class MessageModel extends BasicModel {
	protected static $instances;
	
	protected $table = 'message';
	protected $primaryKey = "message_id";

	public static $messageType = array(
		'likeDiary' => 1,
		'DiaryComment' => 2,
		'leavingMessage' => 3,
		'follow' => 4
	);
	
	public static $messageReadStatus = array(
		'unread' => 1,
		'read' => 2
	);

	private static $cacheExpire = 0;

	/**
     * @return MessageModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new MessageModel();
        }

        return self::$instances;
    }

	public function addMessage($messageType, $senderId, $senderName, $receiverId, $diaryId = 0, $diaryTitle = '') {
		if (!isset($messageType) || empty($messageType) || !in_array($messageType, array_values(self::$messageType))) {
			throw new Exception_BadInput("Invalid message type");
		}	
		
		if ((!isset($senderId) || empty($senderId)) || (!isset($receiverId) || empty($receiverId))) {
			throw new Exception_BadInput("Invalid message sender id or receiver id");
		}
		
		$createTime = $updateTime = time();

		$sql = "INSERT INTO message VALUES (";
		$sql .= "NULL, {$messageType}, {$senderId}, '{$senderName}', {$receiverId}, ";
		$sql .= self::$messageReadStatus['unread'] . ", ";
		$sql .= "{$createTime}, {$updateTime}, {$diaryId}, '{$diaryTitle}', 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE count = count + 1, is_read = " . self::$messageReadStatus['unread'];

		$this->db->update($sql);

		$messageId = $this->db->getLastInsertID();

        $key = McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId, '');
        $messageCacheKeyIndex = MemcachedModel::getInstance()->get($key);
        $messageCacheKeyIndex = empty($messageCacheKeyIndex) ? array() : json_decode($messageCacheKeyIndex, true);

        $messageCacheKey = empty($diaryId) ? $messageType : $messageType . '_' . $diaryId;

        if (empty($messageCacheKeyIndex) || !in_array($messageCacheKey, $messageCacheKeyIndex)) {
            $res = $this->increaseMessageAmountToCache($receiverId);
            if (!$res) {
                $this->setMessageAmountToCache($receiverId, 1);
            }
        }

        $this->setMessageToCache($messageId, $messageType, $senderId, $senderName, $receiverId, $diaryId, $diaryTitle);

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
		
		if (!$existedKey) {
			$messageCacheKeyIndex[] = $messageCacheKey;
			
			//将消息内容写进缓存
			$messageContentCacheKey = self::getMessageContentCacheKey($receiverId, $messageCacheKey);		
			$messageContent = json_encode(array($messageId, $messageType, $senderId, $senderName, $receiverId, $diaryId, $diaryTitle));
			$memcache->set($messageContentCacheKey, $messageContent, self::$cacheExpire);
		
			//将消息缓存key的索引写入缓存
			$memcache->set($key, json_encode($messageCacheKeyIndex), self::$cacheExpire);
		}
		
		//消息内计数。譬如：“n人给你留言”、“n人评论了你的日志”等；
		$messageNumCacheKey = self::getMessageNumCacheKey($receiverId, $messageCacheKey);
		if (!$existedKey) {
			$memcache->set($messageNumCacheKey, 1, self::$cacheExpire);
		} else {
            $memcache->increase($messageNumCacheKey);
		}

	}
	
	public function readMessage($receiverId, $messageId, $messageType, $diaryId) {
		$updateData = array(
			'message_id' => $messageId,
			'is_read' => self::$messageReadStatus['read'],
            'count' => 0
		);

		$this->update($updateData);

		$this->decreaseMessageAmountToCache($receiverId);

		$messageCacheKey = empty($diaryId) ? $messageType : $messageType . '_' . $diaryId;
		MemcachedModel::getInstance()->delete(self::getMessageNumCacheKey($receiverId, $messageCacheKey));
		MemcachedModel::getInstance()->delete(self::getMessageContentCacheKey($receiverId, $messageCacheKey));

		$key = McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId, '');
		$messageCacheKeyIndex = MemcachedModel::getInstance()->get($key);
		$messageCacheKeyIndex = empty($messageCacheKeyIndex) ? array() : json_decode($messageCacheKeyIndex, true);

		$index = array_search($messageCacheKey, $messageCacheKeyIndex);
		if ($index === FALSE) {
			return ;
		}

		array_splice($messageCacheKeyIndex, $index, 1);

		MemcachedModel::getInstance()->set($key, json_encode($messageCacheKeyIndex), self::$cacheExpire);
	}

	public function getMessage($receiverId) {
		$messageCache = $this->getMessageFromCache($receiverId);				
		$messageAmount = $this->getMessageAmountFromCache($receiverId);	

		if (!empty($messageCache) && $messageAmount !== FALSE) {
			return array('message' => $messageCache, 'messageCount' => $messageAmount);	
		}

		$sql = "SELECT * FROM message WHERE `receiver_id`={$receiverId} AND `is_read`=" . self::$messageReadStatus['unread'];
		$messages = $this->db->queryAllRows($sql);
		if ($messages === FALSE) {
			return FALSE;	
		}
		//TODO 写缓存
		return array('message' => $messages, 'messageCount' => count($messages));			
	}

	private function getMessageFromCache($receiverId) {
		$key = self::getMessageCacheKeyIndex($receiverId);

		$memcache = MemcachedModel::getInstance(); 
		$messageCacheKeyIndex = $memcache->get($key);		
		$messageCacheKeyIndex = json_decode($messageCacheKeyIndex, true);	

		if (empty($messageCacheKeyIndex)) {
			return null;
		}

		$messageNumCacheKeys = array();
		$messageContentCacheKeys = array();
		foreach ($messageCacheKeyIndex as $cacheKey) {
			$messageNumCacheKeys[$cacheKey] = self::getMessageNumCacheKey($receiverId, $cacheKey);	
			$messageContentCacheKeys[$cacheKey] = self::getMessageContentCacheKey($receiverId, $cacheKey);
		}

		$caches = MemcachedModel::getInstance()->get(array_merge(array_values($messageContentCacheKeys), array_values($messageNumCacheKeys)));

		$messages = array();
        $messageContentKeys = array('message_id', 'type', 'sender_id', 'sender_name', 'receiver_id', 'diary_id', 'diary_title');
		foreach ($messageCacheKeyIndex as $cacheKey) {
			if (empty($caches[$messageContentCacheKeys[$cacheKey]])) {
				continue;	
			}
			
			$messageContent = json_decode($caches[$messageContentCacheKeys[$cacheKey]], true);
            $messageContent = array_combine($messageContentKeys, $messageContent);
			$messageContent['count'] = empty($caches[$messageNumCacheKeys[$cacheKey]]) ? 1 : $caches[$messageNumCacheKeys[$cacheKey]];

			$messages[] = $messageContent;
		}

		return $messages;
	}

	public function getMessageAmount($receiverId) {
		$amount = $this->getMessageAmountFromCache($receiverId);

		if ($amount !== FALSE) {
			return $amount;
		}

		$amount = $this->getMessageAmountFromDb($receiverId);
		$this->setMessageAmountToCache($receiverId, $amount);

		return $amount;
	}
	
	private function getMessageAmountFromDb($receiverId) {
		$sql = "SELECT COUNT(*) as count FROM message WHERE `receiver_id`={$receiverId} AND `is_read`=" . self::$messageReadStatus['unread'];		
		
		$result = $this->db->queryFirstRow($sql);

		return $result['count'];
	}
	
	private function setMessageAmountToCache($receiverId, $amount) {
		$key = self::getMessageAmountCacheKey($receiverId);	

		MemcachedModel::getInstance()->set($key, $amount, 0);	
	}
	
	private function decreaseMessageAmountToCache($receiverId) {
		$key = self::getMessageAmountCacheKey($receiverId);

		MemcachedModel::getInstance()->decrease($key);	
	}

	private function increaseMessageAmountToCache($receiverId) {
		$key = self::getMessageAmountCacheKey($receiverId);	

		return MemcachedModel::getInstance()->increase($key);
	}

	private function getMessageAmountFromCache($receiverId) {
		$key = self::getMessageAmountCacheKey($receiverId);
		$amount = MemcachedModel::getInstance()->get($key);

		if ($amount === FALSE) {
			return FALSE;
		}

		return $amount;				
	}
	
	private static function getMessageAmountCacheKey($receiverId) {
		return McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId . '_amount', '');
	}	

	private static function getMessageCacheKeyIndex($receiverId) {
		return McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId, '');
	}

	private static function getMessageContentCacheKey($receiverId, $messageCacheKey) {
		return McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId . '_' . $messageCacheKey . '_content', '');
	}
	
	private static function getMessageNumCacheKey($receiverId, $messageCacheKey) {
		return McKeyModel::getInstance()->forCompanyInfo('msg', $receiverId . '_' . $messageCacheKey . '_num', '');
	}
}