<?php
/***************************************************************************
 * 
 * Copyright (c) 2009 Active.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
/**
 * Wrapper class for Memcache, mainly solve the multi-machineroom problem
 * 
 * All interfaces without 'All' as postfix will access servers in current 
 * machineroom only, if you want to update a cache item, you should use
 * interfaces with 'All' as postfix only if the memcached cluster is cross-machineroom.
 * 
 * Deploy the memcached cluster as cross-machineroom will simplify our interface and
 * bussiness logic, but as there exists some delay time(1~10ms) when cross-machineroom,
 * so most of the time, we will deploy the memcached cluster in multiple machineroom,
 * then get/add cache item to the current machineroom only for performance and delete
 * the cache item in all machinerooms when we need to update a cache item for data consistency.
 * 
 * @category              libbrary
 * @package		Memcache
 * @author		scao <sid.cao@activenetwork.com>
 * @version		$Revision: 1.0 $ 
 */
class Memcache_Handler
{
	/**
	 * Memcache_Handler instance array
	 * @var array
	 */
	protected static $instances = array();
	
	/**
	 * Default Memcached cluster name
	 * @var string
	 */
	protected static $defaultCluster = 'default';
	
	/**
	 * Current memcached cluster name
	 * @var string
	 */
	protected $currentCluster = '';
	
	/**
	 * Current machineroom
	 * @var string
	 */
	protected $currentIDC = '';
	
	/**
	 * Memcache_Handler instance for current machineroom
	 * @var Memcache_Handler
	 */
	protected $currentMemcache = null;
	
	/**
	 * Memcache_Handler instance which contains the whole memcached cluster
	 * @var Memcache_Handler
	 */
	protected $wholeMemcacheCluster = null;
	
	/**
	 * Memcache_Handler instance array for current cluster in all machineroom
	 * 
	 * Delete command should be sent to all machineroom for current cluster,
	 * and some add/set command should also do this according to the bussiness logic
	 * 
	 * @var array
	 */
	protected $allMemcache = array();
	
	/**
	 * Error message for the last memcache operation
	 * @var string
	 */
	private $errmsg = '';
	
	/**
	 * Set the default cluster name
	 * 
	 * @param string $cluster memcached cluster name
	 */
	public static function setDefaultCluster($cluster)
	{
		self::$defaultCluster = $cluster;
	}
	
	/**
	 * Get Memcache_Handler instance for the specified memcached cluster
	 * 
	 * @param string $cluster	memcached cluster name
	 * @return Memcache_Handler
	 */
	public static function & getInstance($cluster = '')
	{
		if (empty($cluster)) {
			$cluster = self::$defaultCluster;
		}
		if (empty(self::$instances[$cluster])) {
			if (isset(Conf_Memcached::$arrMemCacheServer[$cluster])) {
				self::$instances[$cluster] = new Memcache_Handler($cluster);
			} else {
				self::$instances[$cluster] = false;
			}
		}
		return self::$instances[$cluster];
	}
	
	/**
	 * Create Memcache_Handler instance for the specified memcached cluster
	 * 
	 * @param string $cluster	memcache cluter name
	 */
	protected function __construct($cluster)
	{
		$this->currentCluster = $cluster;
		$this->currentIDC = defined('CURRENT_CONF') ? CURRENT_CONF : 'lt';
		$this->initCurrentMemCache();
	}
	
	/**
	 * Set the current IDC, if the current IDC is changed, the Memcache_Handler instance
	 * for current machineroom will be recreated. It will not be used most of the time.
	 * 
	 * @param string $idc
	 */
	public function setCurrentIDC($idc)
	{
		$cluster = Conf_Memcached::$arrMemCacheServer[$this->currentCluster];
		if (isset($cluster[$idc]) && $this->currentIDC != $idc) {
			$this->currentIDC = $idc;
			$this->currentMemcache = null;
			$this->initCurrentMemCache();
		}
	}
	
	/**
	 * Get the last error message for memcache operation
	 * 
	 * @return string
	 */
	public function error()
	{
		return $this->errmsg;
	}

	/**
	 * Enable automatic compression for large values
	 * 
	 * @param int $threshold		Controls the minimum value length before attempting 
	 * 								to compress automatically
	 * @param double $min_saving	Specifies the minimum amount of savings to actually
	 * 								store the value compressed. The supplied value must
	 * 								be between 0 and 1. Default value is 0.2 giving a 
	 * 								minimum 20% compression savings	
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-setcompressthreshold.php
	 */
	public function setCompressThreshold($threshold, $min_saving = 0.2)
	{
		$res = $this->currentMemcache->setCompressThreshold($threshold, $min_saving);
		if (!$res) {
			$this->errmsg = "set compress threshold for memcached[" .
				"{$this->currentCluster}:{$this->currentIDC} failed";
		}
		return $res;
	}

	/**
	 * Enable automatic compression for large values
	 * 
	 * @param int $threshold		Controls the minimum value length before attempting 
	 * 								to compress automatically
	 * @param double $min_saving	Specifies the minimum amount of savings to actually
	 * 								store the value compressed. The supplied value must
	 * 								be between 0 and 1. Default value is 0.2 giving a 
	 * 								minimum 20% compression savings	
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-setcompressthreshold.php
	 */
	public function setCompressThresholdEx($threshold, $min_saving = 0.2)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->setCompressThreshold($threshold, $min_saving);
		if (!$res) {
			$this->errmsg = "set compress threshold for memcached[{$this->currentCluster}] failed";
		}
		return $res;
	}
	
	/**
	 * Add an item to the server of current machineroom
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mix $var		The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see	http://cn.php.net/manual/en/function.memcache-add.php
	 */
	public function add($key, $var, $flag = 0, $expire = 0)
	{
		$res = $this->currentMemcache->add($key, $var, $flag, $expire);
		if (!$res) {
			$this->errmsg = "add key[$key] to memcached[" .
				"{$this->currentCluster}:{$this->currentIDC}]" .
				" failed or key has already exists";
		}
		return $res;
	}
	
	/**
	 * Add an item to the whole memcached cluster
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mix $var		The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see	http://cn.php.net/manual/en/function.memcache-add.php
	 */
	public function addEx($key, $var, $flag = 0, $expire = 0)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->add($key, $var, $flag, $expire);
		if (!$res) {
			$this->errmsg = "add key[$key] to memcached[" .
				"{$this->currentCluster}}] failed or key has already exists";
		}
		return $res;
	}

	/**
	 * Retrieve item from the server of current machineroom
	 * 
	 * @param string|array $mixedKey	The key or array of keys to fetch
	 * @param int|array $mixedFlags		If present, flags fetched along with 
	 * 									the values will be written to this parameter
	 * @return string|array
	 * @see http://cn.php.net/manual/en/function.memcache-get.php
	 */
	public function get($mixedKey, & $mixedFlags = false)
	{
		$res = $this->currentMemcache->get($mixedKey, $mixedFlags);
		if ($res === false) {
			if (is_string($mixedKey)) {
				$this->errmsg = "get key[$mixedKey] from memcached[" .
					"{$this->currentCluster}:{$this->currentIDC}]" .
					" failed or key not exists";
			} else {
				$this->errmsg = "get multiple keys from memcached[" .
					"{$this->currentCluster}:{$this->currentIDC}]" .
					" failed or no keys exists";
			}
		}
		return $res;
	}
	
	/**
	 * Retrieve item from the whole memcached cluster
	 * 
	 * @param string|array $mixedKey	The key or array of keys to fetch
	 * @param int|array $mixedFlags		If present, flags fetched along with 
	 * 									the values will be written to this parameter
	 * @return string|array
	 * @see http://cn.php.net/manual/en/function.memcache-get.php
	 */
	public function getEx($mixedKey, & $mixedFlags = false)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->get($mixedKey, $mixedFlags);
		if ($res === false) {
			if (is_string($mixedKey)) {
				$this->errmsg = "get key[$mixedKey] from memcached[" .
					"{$this->currentCluster}] failed or key not exists";
			} else {
				$this->errmsg = "get multiple keys from memcached[" .
					"{$this->currentCluster}] failed or no keys exists";
			}
		}
		return $res;
	}

	/**
	 * Store data at the server of current machineroom
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-set.php
	 */
	public function set($key, $var, $flag = 0, $expire = 0)
	{
		$res = $this->currentMemcache->set($key, $var, $flag, $expire);
		if (!$res) {
			$this->errmsg = "set key[$key] to memcached[" .
				"{$this->currentCluster}:{$this->currentIDC}] failed";
		}
		return $res;
	}
	
	/**
	 * Store data at the whole memcached cluster
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-set.php
	 */
	public function setEx($key, $var, $flag = 0, $expire = 0)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->set($key, $var, $flag, $expire);
		if (!$res) {
			$this->errmsg = "set key[$key] to memcached[" .
				"{$this->currentCluster}] failed";
		}
		return $res;
	}

	/**
	 * Store data at the server in all machineroom
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-set.php
	 */
	public function setAll($key, $var, $flag = 0, $expire = 0)
	{
		$this->initAllMemCache();
		$this->errmsg = '';
		foreach ($this->allMemcache as $machineroom => $memcache) {
			if (!$memcache->set($key, $var, $flag, $expire)) {
				$this->errmsg .= $machineroom . ',';
			}
		}
		if (empty($this->errmsg)) {
			$this->errmsg = 'success';
			return true;
		} else {
			$this->errmsg = "set key[$key] to memcached[" .
				"{$this->currentCluster}:{$this->errmsg}] failed";
			return false;
		}
	}
	
	/**
	 * Increment cache item's value at the server of current machineroom
	 * 
	 * @param string $key	Key of the item to increment
	 * @param int $value	Increment the item by value
	 * @return int
	 * @see http://cn.php.net/manual/en/function.memcache-increment.php
	 */
	public function increment($key, $value = 1)
	{
		$res = $this->currentMemcache->increment($key, $value);
		if (!$res) {
			$this->errmsg = "increment key[$key] with value[$value] at " .
				"memcached[{$this->currentCluster}:{$this->currentIDC}] failed";
		}
		return $res;
	}
	
	/**
	 * Increment cache item's value at the server of the whole memcached cluster
	 * 
	 * @param string $key	Key of the item to increment
	 * @param int $value	Increment the item by value
	 * @return int
	 * @see http://cn.php.net/manual/en/function.memcache-increment.php
	 */
	public function incrementEx($key, $value = 1)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->increment($key, $value);
		if (!$res) {
			$this->errmsg = "increment key[$key] with value[$value] at " .
				"memcached[{$this->currentCluster}] failed";
		}
		return $res;
	}
	
	/**
	 * Decrement cache item's value at the server of current machineroom
	 * 
	 * @param string $key	Key of the item to increment
	 * @param int $value	Increment the item by value
	 * @return int
	 * @see http://cn.php.net/manual/en/function.memcache-decrement.php
	 */
	public function decrement($key, $value = 1)
	{
		$res = $this->currentMemcache->decrement($key, $value);
		if (!$res) {
			$this->errmsg = "decrement key[$key] with value[$value] at " .
				"memcached[{$this->currentCluster}:{$this->currentIDC}] failed";
		}
		return $res;
	}
	
	/**
	 * Decrement cache item's value at the server of the whole memcached cluster
	 * 
	 * @param string $key	Key of the item to increment
	 * @param int $value	Increment the item by value
	 * @return int
	 * @see http://cn.php.net/manual/en/function.memcache-decrement.php
	 */
	public function decrementEx($key, $value = 1)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->decrement($key, $value);
		if (!$res) {
			$this->errmsg = "decrement key[$key] with value[$value] at " .
				"memcached[{$this->currentCluster}] failed";
		}
		return $res;
	}
	
	/**
	 * Replace value of the <b>existing</b> item in current machineroom
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-replace.php
	 */
	public function replace($key, $var, $flag = 0, $expire = 0)
	{
		$res = $this->currentMemcache->replace($key, $var, $flag, $expire);
		if (!$res) {
			$this->errmsg = "replace key[$key] in memcached[" .
				"{$this->currentCluster}:{$this->currentIDC}] failed";
		}
		return $res;
	}
	
	/**
	 * Replace value of the <b>existing</b> item in the whole memcached cluster
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-replace.php
	 */
	public function replaceEx($key, $var, $flag = 0, $expire = 0)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->replace($key, $var, $flag, $expire);
		if (!$res) {
			$this->errmsg = "replace key[$key] in memcached[" .
				"{$this->currentCluster}] failed";
		}
		return $res;
	}
	
	/**
	 * Replace value of the <b>existing</b> item in all machinerooms
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-replace.php
	 */
	public function replaceAll($key, $var, $flag = 0, $expire = 0)
	{
		$this->initAllMemCache();
		$this->errmsg = '';
		foreach ($this->allMemcache as $machineroom => $memcache) {
			if (!$memcache->replace($key, $var, $flag, $expire)) {
				$this->errmsg .= $machineroom . ',';
			}
		}
		if (empty($this->errmsg)) {
			$this->errmsg = 'success';
			return true;
		} else {
			$this->errmsg = "replace key[$key] in memcached[" .
				"{$this->currentCluster}:{$this->errmsg}] failed";
			return false;
		}
	}

	/**
	 * Flush all existing items at the server of current machineroom
	 * 
	 * @see http://cn.php.net/manual/en/function.memcache-flush.php
	 */
	public function flush()
	{
		$res = $this->currentMemcache->flush();
		if (!$res) {
			$this->errmsg = "flush memcached[" .
				"{$this->currentCluster}:{$this->currentIDC}] failed";
		}
		return $res;
	}
	
	/**
	 * Flush all existing items at the server of the whole memcached cluster
	 * 
	 * @see http://cn.php.net/manual/en/function.memcache-flush.php
	 */
	public function flushEx()
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->flush();
		if (!$res) {
			$this->errmsg = "flush memcached[{$this->currentCluster}] failed";
		}
		return $res;
	}

	/**
	 * Flush all existing items at the server of all machineroom
	 * 
	 * @see http://cn.php.net/manual/en/function.memcache-flush.php
	 */
	public function flushAll()
	{
		$this->initAllMemCache();
		$this->errmsg = '';
		foreach ($this->allMemcache as $machineroom => $memcache) {
			if (!$memcache->flush()) {
				$this->errmsg .= $machineroom . ',';
			}
		}
		if (empty($this->errmsg)) {
			$this->errmsg = 'success';
			return true;
		} else {
			$this->errmsg = "flush memcached[" .
				"{$this->currentCluster}:{$this->errmsg}] failed";
			return false;
		}
	}

	/**
	 * Delete item from the server of current machineroom
	 * 
	 * @param string $key	The key associated with the item to delete
	 * @param int $timeout	Execution time of the item. If it's equal to zero, 
	 * 						the item will be deleted right away whereas if you 
	 * 						set it to 30, the item will be deleted in 30 seconds
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-delete.php
	 */
	public function delete($key, $timeout = 0)
	{
		$res = $this->currentMemcache->delete($key, $timeout);
		if (!$res) {
			$this->errmsg = "delete key[$key] from memcached[" .
				"{$this->currentCluster}:{$this->currentIDC}] failed";
		}
		return $res;
	}
	
	/**
	 * Delete item from the server of the whole memcached cluster
	 * 
	 * @param string $key	The key associated with the item to delete
	 * @param int $timeout	Execution time of the item. If it's equal to zero, 
	 * 						the item will be deleted right away whereas if you 
	 * 						set it to 30, the item will be deleted in 30 seconds
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-delete.php
	 */
	public function deleteEx($key, $timeout = 0)
	{
		$this->initWholeMemcacheCluster();
		$res = $this->wholeMemcacheCluster->delete($key, $timeout);
		if (!$res) {
			$this->errmsg = "delete key[$key] from memcached[" .
				"{$this->currentCluster}] failed";
		}
		return $res;
	}

	/**
	 * Delete item from the server of all machineroom
	 * 
	 * @param string $key	The key associated with the item to delete
	 * @param int $timeout	Execution time of the item. If it's equal to zero, 
	 * 						the item will be deleted right away whereas if you 
	 * 						set it to 30, the item will be deleted in 30 seconds
	 * @return bool
	 * @see http://cn.php.net/manual/en/function.memcache-delete.php
	 */
	public function deleteAll($key, $timeout = 0)
	{
		$this->initAllMemCache();
		$this->errmsg = '';
		foreach ($this->allMemcache as $machineroom => $memcache) {
			if (!$memcache->delete($key, $timeout)) {
				$this->errmsg .= $machineroom . ',';
			}
		}
		if (empty($this->errmsg)) {
			$this->errmsg = 'success';
			return true;
		} else {
			$this->errmsg = "delete key[$key] from memcached[" .
				"{$this->currentCluster}:{$this->errmsg}] failed";
			return false;
		}
	}

	/**
	 * Initialize the memcache instance for the current machineroom
	 */
	protected function initCurrentMemCache()
	{
		$this->errmsg = 'success';
		if (empty($this->currentMemcache)) {
			$this->currentMemcache = new Memcache();
			$arrServer = Conf_Memcached::$arrMemCacheServer[$this->currentCluster][$this->currentIDC];
			foreach ($arrServer as $server) {
				$this->currentMemcache->addServer($server['host'],
												  $server['port'],
												  Conf_Memcached::PERSISTENT,
												  $server['weight'],
												  Conf_Memcached::TIMEOUT,
												  Conf_Memcached::RETRY_INTERVAL,
												  true, null,
												  Conf_Memcached::TIMEOUT_MS);
			}
		}
	}

	/**
	 * Initialize the memcache instance for all machineroom
	 */
	protected function initAllMemCache()
	{
		if (empty($this->allMemcache)) {
			//$this->allMemcache = array();
			$this->allMemcache[$this->currentIDC] = $this->currentMemcache;
			$arrCluster = Conf_Memcached::$arrMemCacheServer[$this->currentCluster];
			foreach ($arrCluster as $machineroom => $arrServer) {
				if (strcasecmp($machineroom, $this->currentIDC) === 0) {
					continue;
				}
				$memcache = new Memcache();
				foreach ($arrServer as $server) {
					$memcache->addserver($server['host'],
                                        $server['port'],
                                        Conf_Memcached::PERSISTENT,
                                        $server['weight'],
                                        Conf_Memcached::TIMEOUT,
                                        Conf_Memcached::RETRY_INTERVAL,
                                        true, null,
                                        Conf_Memcached::TIMEOUT_MS);
				}
				$this->allMemcache[$machineroom] = $memcache;
			}
		}
	}
	
	/**
	 * Initialize the memcache instance for the whole memcached cluster
	 */
	protected function initWholeMemcacheCluster()
	{
		if (empty($this->wholeMemcacheCluster)) {
			$memcache = new Memcache();
			$arrCluster = Conf_Memcached::$arrMemCacheServer[$this->currentCluster];
			foreach ($arrCluster as $machineroom => $arrServer) {
				foreach ($arrServer as $server) {
					$memcache->addserver($server['host'],
										 $server['port'],
										 Conf_Memcached::PERSISTENT,
										 $server['weight'],
										 Conf_Memcached::TIMEOUT,
										 Conf_Memcached::RETRY_INTERVAL,
										 true, null,
										 Conf_Memcached::TIMEOUT_MS);
				}
			}
			$this->wholeMemcacheCluster = $memcache;
		}
	}
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>