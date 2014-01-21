<?php

date_default_timezone_set('Asia/Chongqing');

define('WWW_ROOT', '/data/wwwroot/i365day');
define('PROCESS_START_TIME', microtime(true) * 1000);
define('HTTP_DOMAIN', 'http://' . DOMAIN);
define('STATIC_DOMAIN', HTTP_DOMAIN . '/statics');
define('LOG_PATH', '/opt/active/logs');
define('I365DAY', 'i365day');
define('DIARY_PIC_DIR', WWW_ROOT . DIRECTORY_SEPARATOR . 'statics' . DIRECTORY_SEPARATOR . 'kvt');
define('DIARY_PIC_SRC', '/statics' . DIRECTORY_SEPARATOR . 'kvt');

define('MAINTENANCE_TIME', '2013-06-20 12:00');
define('MAINTENANCE_REMIND_DAYS', 7);
ini_set("display_errors", '');
ini_set("session.cookie_httponly", 1);
//日志打印相关参数定义
$GLOBALS['LOG'] = array(
    'intLevel' => 7, //notice, warning, fatal
    'strLogFile' => LOG_PATH . DIRECTORY_SEPARATOR . 'cost.log',
    'arrSelfLogFiles' => array(
        'cost' => LOG_PATH . DIRECTORY_SEPARATOR . 'cost.sdf.log',
        'db' => LOG_PATH . DIRECTORY_SEPARATOR . 'db.sdf.log',
    ),
);
?>
