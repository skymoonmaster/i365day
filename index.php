<?php

define("APPLICATION_PATH", dirname(__FILE__));
require_once(APPLICATION_PATH . '/application/common/env_init.php');
$app = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
/**
 * 开启捕获异常
 */
#   Yaf_Dispatcher::getInstance()->catchException(TRUE);
try {
    $app->bootstrap()->run();
} catch (Exception_BadInput $e) {
    Util_CLog::setFile(basename($e->getFile()));
    Util_CLog::setLine($e->getLine());
    Util_CLog::warning($e->getMessage());
    var_dump($e->getMessage());
    #header("Location: /error/");
} catch (Exception_Login $e) {
    Util_CLog::setFile(basename($e->getFile()));
    Util_CLog::setLine($e->getLine());
    Util_CLog::warning($e->getMessage());
    var_dump($e->getMessage());
    #header("Location: /login/index/error/show");
} catch (Exception_Ajax $e) {
    Util_CLog::setFile(basename($e->getFile()));
    Util_CLog::setLine($e->getLine());
    Util_CLog::warning($e->getLogMsg());
    var_dump($e->getMessage());
    #echo $e->getoutPutMsg();
} catch (Exception $e) {
    Util_CLog::setFile(basename($e->getFile()));
    Util_CLog::setLine($e->getLine());
    Util_CLog::warning($e->getMessage());
    var_dump($e->getMessage());
    #header("Location: /error/");
}
/**
 * The file and line should been cleared for the log without exception
 * 
 */
Util_CLog::setFile('');
Util_CLog::setLine('');
?>
