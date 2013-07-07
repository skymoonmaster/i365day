<?php

/* * *************************************************************************
 *
 * Copyright (c) 2013 Active.com, Inc. All Rights Reserved
 *
 * ************************************************************************ */

/**
 * Config for bdMemcache clusters
 * 
 * @category              cache
 * @package		Memcache
 * @author		scao <sid.cao@activenetwork.com>
 * @version		$Revision: 1.0 $ 
 */
class Conf_Memcached {
    /**
     * 是否是长连接
     * @var bool
     */

    const PERSISTENT = false;

    /**
     * 连接超时时间，秒级
     * @var int
     */
    const TIMEOUT = 1;

    /**
     * 连接超时时间，毫秒级，优先级比TIMEOUT配置高
     * @var int
     */
    const TIMEOUT_MS = 100;

    /**
     * 健康检查的重试时间间隔，秒级
     * @var int
     */
    const RETRY_INTERVAL = 2;

    //memcached配置，两个机房的配置都写进去，通过CURRENT_CONF来指定使用哪个机房的配置
    static $arrMemCacheServer = array(
        'default' => array(
            'lt' => array(
                array('host' => 'stg-viewmobile-memcached-01.dev.activenetwork.com',
                    'port' => 11211,
                    'weight' => 1,
                ),
                array('host' => 'stg-viewmobile-memcached-02.dev.activenetwork.com',
                    'port' => 11211,
                    'weight' => 1,
                ),
            ),
            'dx' => array(
                array('host' => 'stg-viewmobile-memcached-01.dev.activenetwork.com',
                    'port' => 11211,
                    'weight' => 1,
                ),
                array('host' => 'stg-viewmobile-memcached-02.dev.activenetwork.com',
                    'port' => 11211,
                    'weight' => 1,
                ),
            ),
        ),
    );

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
