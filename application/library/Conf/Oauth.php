<?php
/**
 */

class Conf_Oauth {
    public static $appIds = array(
        'i365day' => 1,
        'douban' => 2,
        'weibo' => 3
    );

    public static $doubanConf = array(
        // 必选参数，豆瓣应用public key。
        'client_id' => '0449c1ff7763909e0cdd45fe662ab1da',
        // 必选参数，豆瓣应用secret key。
        'secret' => '89e943e1edb048eb',
        // 必选参数，用户授权后的回调链接。
        'redirect_uri' => 'http://www.i365day.com/callback/douban',
        // 可选参数（默认为douban_basic_common），授权范围。
        'scope' => 'douban_basic_common,shuo_basic_r,shuo_basic_w',
        // 可选参数（默认为false），是否在header中发送accessToken。
        'need_permission' => true
    );
}