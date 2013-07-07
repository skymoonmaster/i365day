<?php

define("APPLICATION_PATH", dirname(__FILE__) . '/../../');

require_once(APPLICATION_PATH . '/application/common/env_init.php');
$app = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
$app->bootstrap();
ini_set("display_errors", "On");
error_reporting(E_ALL);

if (!is_array($argv) || count($argv) == 0) {
    echo "\r\nBad input for cli\r\n";
    exit;
}
$filepath = dirname(__FILE__) . '/' . $argv[1] . '.php';
if (!isset($argv[1])) {
    echo "\r\nBad input task name for cli\r\n";
    exit;
}
if (!file_exists($filepath)) {
    echo "\r\nBad input task name for cli\r\n";
    exit;
}
require_once(dirname(__FILE__) . '/BasicDaemon.php');
require_once($filepath);


try {
    $classname = 'Script_' . $argv[1];
    array_shift($argv);
    $script = new $classname();
    call_user_func_array(array($script, 'run'), $argv);
} catch (Exception_BadInput $e) {
    Util_CLog::setFile(basename($e->getFile()));
    Util_CLog::setLine($e->getLine());
    Util_CLog::warning($e->getMessage());
} catch (Exception_Login $e) {
    Util_CLog::setFile(basename($e->getFile()));
    Util_CLog::setLine($e->getLine());
    Util_CLog::warning($e->getMessage());
} catch (Exception $e) {
    Util_CLog::setFile(basename($e->getFile()));
    Util_CLog::setLine($e->getLine());
    Util_CLog::warning($e->getMessage());
}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>