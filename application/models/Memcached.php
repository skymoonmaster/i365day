<?php

class MemcachedModel {

    /**
     * @var MemcachedModel 
     */
    protected static $instances;

    /**
     * @return MemcachedModel 
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new MemcachedModel();
        }
        return self::$instances;
    }

    public function get($key) {
        $mc = Memcache_Handler::getInstance();
        if (is_array($key)) {
            foreach ($key as $key_key => $key_value) {
                $key[$key_key] = $key_value;
            }
            return $mc->get($key);
        }
        return $mc->get($key);
    }

    public function setAll($key, $value, $lifetime) {
        $mc = Memcache_Handler::getInstance();
        return $mc->setAll($key, $value, false, $lifetime);
    }

    public function deleteAll($key) {
        $mc = Memcache_Handler::getInstance();
        return $mc->deleteAll($key);
    }

    public function delete($key) {
        $mc = Memcache_Handler::getInstance();
        return $mc->delete($key);
    }

    public function set($key, $value, $lifetime) {
        $mc = Memcache_Handler::getInstance();
        return $mc->set($key, $value, false, $lifetime);
    }

    public function getMCDataMagic($key, $func, $lifetime) {
        $argv = func_get_args();
        return $this->doGetMCDataMagic(false, $key, $func, $lifetime, $argv);
    }

    public function getMCDataMagicAll($key, $func, $lifetime) {
        $argv = func_get_args();
        return $this->doGetMCDataMagic(true, $key, $func, $lifetime, $argv);
    }

    private function doGetMCDataMagic($needCrossMachineroom, $key, $func, $lifetime, $argv) {
        if (empty($key) && empty($func)) {
            CLog::warning('one of params is empty');
            return false;
        }
        $ret = $this->get($key);
        if ($ret !== false) {
            return $ret;
        }
        array_shift($argv);
        array_shift($argv);
        array_shift($argv);

        $ret = call_user_func_array($func, $argv);
        if ($ret !== false && isset($ret)) {
            if (!$needCrossMachineroom) {
                $this->set($key, $ret, $lifetime);
            } else {
                $this->setAll($key, $ret, $lifetime);
            }
        }
        return $ret;
    }
	
	public function increase($key, $value = 1) {
		$mc = Memcache_Handler::getInstance();
		return $mc->increment($key, $value);
	}

	public function decrease($key, $value = 1) {
		$mc = Memcache_Handler::getInstance();
		return $mc->decrement($key, $value);
	}
}
