<?php

/**
 * 视图引擎定义
 * Smarty/Adapter.php
 */
class Oauth_Adapter {
    /**
     * @var Oauth_Adapter
     */
    protected static $instances;
    /**
     * @return Oauth_Adapter
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new Oauth_Adapter();
        }
        return self::$instances;
    }
    /**
     * @return DoubanOauth
     * @param string $type
     */
    public function createOauthModel($type){
        switch ($type){
            case  'douban' :
                require __DIR__ . '/Douban/src/DoubanOauth.php';
                // 生成一个豆瓣Oauth类实例
                return new DoubanOauth(Conf_Oauth::$doubanConf);
        }
    }
}

?>
