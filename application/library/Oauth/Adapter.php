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
                /* ------------实例化Oauth2--------------- */
                $appConfig = array(
                            // 必选参数，豆瓣应用public key。
                            'client_id' => '07a6c264478cf2c116f4472cd6a6d843',
                            // 必选参数，豆瓣应用secret key。
                            'secret' => '4cc7d98ea7cc9813',
                            // 必选参数，用户授权后的回调链接。
                            'redirect_uri' => 'http://www.i365day-lc.com/callback/douban',
                            // 可选参数（默认为douban_basic_common），授权范围。
                            'scope' => 'douban_basic_common,shuo_basic_r,shuo_basic_w',
                            // 可选参数（默认为false），是否在header中发送accessToken。
                            'need_permission' => true
                            );
                // 生成一个豆瓣Oauth类实例
                return new DoubanOauth($appConfig);
        }
    }
}

?>
