<?php

/**
 * @file Util_CLog.class.php
 * @version $Revision: 1.1 $
 * @brief class for logging
 *
 * */

/**
 * @example:
 *
  <?php
  require_once('Util_CLog.class.php');

  $GLOBALS['LOG'] = array(
  'intLevel			=> 0x07,		//fatal, warning, notice
  'strLogFile'		=> '/home/space/space/log/test.log',	//test.log.wf will be the wf log file
  'arrSelfLogFiles'	=> array(
  'acstat'	=> '/home/space/space/log/acstat.sdf.log',
  'bhstat'	=> '/home/space/space/log/bhstat.sdf.log',
  ),
  );

  $str = 'biaji';
  Util_CLog::notice($str);
  Util_CLog::fatal($str);
  Util_CLog::warning($str);
  Util_CLog::debug($str);
  Util_CLog::selfLog('acstat', $str);
  Util_CLog::selfLog('bhstat', $str);

 * */
class Util_CLog {

    const LOG_LEVEL_NONE = 0x00;
    const LOG_LEVEL_FATAL = 0x01;
    const LOG_LEVEL_WARNING = 0x02;
    const LOG_LEVEL_NOTICE = 0x04;
    const LOG_LEVEL_TRACE = 0x08;
    const LOG_LEVEL_DEBUG = 0x10;
    const LOG_LEVEL_ALL = 0xFF;

    public static $arrLogLevels = array(
        self::LOG_LEVEL_NONE => 'NONE',
        self::LOG_LEVEL_FATAL => 'FATAL',
        self::LOG_LEVEL_WARNING => 'WARNING',
        self::LOG_LEVEL_NOTICE => 'NOTICE',
        self::LOG_LEVEL_TRACE => 'TRACE',
        self::LOG_LEVEL_DEBUG => 'DEBUG',
        self::LOG_LEVEL_ALL => 'ALL',
    );
    protected $intLevel;
    protected $strLogFile;
    protected $arrSelfLogFiles;
    protected $intLogId;
    protected $intStartTime;
    protected $file;
    protected $line;

    /**
     * @var Util_CLog 
     */
    private static $instance = null;

    private function __construct($arrLogConfig, $intStartTime) {
        $this->intLevel = intval($arrLogConfig['intLevel']);
        $this->strLogFile = $arrLogConfig['strLogFile'];
        $this->arrSelfLogFiles = $arrLogConfig['arrSelfLogFiles'];
        $this->intLogId = self::__logId();
        $this->intStartTime = $intStartTime;
    }

    /**
     * @return Util_CLog
     */
    public static function getInstance() {
        if (self::$instance === null) {
            $intStartTime = defined('PROCESS_START_TIME') ? PROCESS_START_TIME : microtime(true) *
                    1000;
            self::$instance = new Util_CLog($GLOBALS['LOG'], $intStartTime);
        }
        return self::$instance;
    }

    /**
     * Write raw string to log file
     * @param string $str
     * @return int Return log string length if success, or null if failed
     */
    public static function rawlog($str) {
        $log = Util_CLog::getInstance();
        return $log->writeRawLog($str);
    }

    /**
     * Write self defined log
     * 
     * @param string $strKey	key of the self defined log file
     * @param string $str		self defined log string
     * @param array $arrArgs	params in k/v format to be write into the log
     */
    public static function selflog($strKey, $str, $arrArgs = null) {
        $log = Util_CLog::getInstance();
        return $log->writeSelfLog($strKey, $str, $arrArgs);
    }

    /**
     * Write debug log
     * 
     * @param string $str		Self defined log string
     * @param int $errno		errno to be write into log
     * @param array $arrArgs	params in k/v format to be write into log
     * @param int $depth		depth of the function be packaged
     */
    public static function debug($str, $errno = 0, $arrArgs = null, $depth = 0) {
        $log = Util_CLog::getInstance();
        return $log->writeLog(self::LOG_LEVEL_DEBUG, $str, $errno, $arrArgs, $depth + 1);
    }

    /**
     * Write trace log
     * 
     * @param string $str		Self defined log string
     * @param int $errno		errno to be write into log
     * @param array $arrArgs	params in k/v format to be write into log
     * @param int $depth		depth of the function be packaged
     */
    public static function trace($str, $errno = 0, $arrArgs = null, $depth = 0) {
        $log = Util_CLog::getInstance();
        return $log->writeLog(self::LOG_LEVEL_TRACE, $str, $errno, $arrArgs, $depth + 1);
    }

    public static function notice($str, $errno = 0, $arrArgs = null, $depth = 0) {
        $log = Util_CLog::getInstance();
        return $log->writeLog(self::LOG_LEVEL_NOTICE, $str, $errno, $arrArgs, $depth + 1);
    }

    /**
     * Write warning log
     * 
     * @param string $str		Self defined log string
     * @param int $errno		errno to be write into log
     * @param array $arrArgs	params in k/v format to be write into log
     * @param int $depth		depth of the function be packaged
     */
    public static function warning($str, $errno = 0, $arrArgs = null, $depth = 0) {
        $log = Util_CLog::getInstance();
        return $log->writeLog(self::LOG_LEVEL_WARNING, $str, $errno, $arrArgs, $depth + 1);
    }

    /**
     * Write fatal log
     * 
     * @param string $str		Self defined log string
     * @param int $errno		errno to be write into log
     * @param array $arrArgs	params in k/v format to be write into log
     * @param int $depth		depth of the function be packaged
     */
    public static function fatal($str, $errno = 0, $arrArgs = null, $depth = 0) {
        $log = Util_CLog::getInstance();
        return $log->writeLog(self::LOG_LEVEL_FATAL, $str, $errno, $arrArgs, $depth + 1);
    }

    /**
     * Get logid for current http request
     * @return int
     */
    public static function logId() {
        return Util_CLog::getInstance()->intLogId;
    }

    /**
     * Get the real remote client's IP
     * @return string
     */
    public static function getClientIP() {
        if (isset($_SERVER['HTTP_CLIENTIP'])) {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
                $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1') {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ips[0];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }

        $pos = strpos($ip, ',');
        if ($pos > 0) {
            $ip = substr($ip, 0, $pos);
        }

        return trim($ip);
    }

    public function writeLog($intLevel, $str, $errno = 0, $arrArgs = null, $depth = 0) {
        if ($intLevel > $this->intLevel || !isset(self::$arrLogLevels[$intLevel])) {
            return;
        }

        $strLevel = self::$arrLogLevels[$intLevel];

        $strLogFile = $this->strLogFile;
        if (($intLevel & self::LOG_LEVEL_WARNING) || ($intLevel & self::LOG_LEVEL_FATAL)) {
            $strLogFile .= '.wf';
        }
        $file = $this->getFile($depth + 1);
        $line = $this->getLine($depth + 1);
        $strArgs = '';
        if (is_array($arrArgs) && count($arrArgs) > 0) {
            foreach ($arrArgs as $key => $value) {
                $strArgs .= $key . "[$value] ";
            }
        }

        $intTimeUsed = microtime(true) * 1000 - $this->intStartTime;

        $str = sprintf("%s: %s [%s:%d] errno[%d] ip[%s] logId[%u] uri[%s] time_used[%d] %s%s\n", $strLevel, date('m-d H:i:s:', time()), $file, $line, $errno, self::getClientIP(), $this->intLogId, isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', $intTimeUsed, $strArgs, $str);

        return file_put_contents($strLogFile, $str, FILE_APPEND);
    }

    public function writeSelfLog($strKey, $str, $arrArgs = null) {
        if (isset($this->arrSelfLogFiles[$strKey])) {
            $strLogFile = $this->arrSelfLogFiles[$strKey];
        } else {
            return;
        }

        $strArgs = '';
        if (is_array($arrArgs) && count($arrArgs) > 0) {
            foreach ($arrArgs as $key => $value) {
                $strArgs .= $key . "[$value] ";
            }
        }

        $str = sprintf("%s: %s ip[%s] logId[%u] uri[%s] %s%s\n", $strKey, date('m-d H:i:s:', time()), self::getClientIP(), $this->intLogId, isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', $strArgs, $str);

        return file_put_contents($strLogFile, $str, FILE_APPEND);
    }

    public function writeRawLog($str) {
        $strLogFile = $this->strLogFile;
        return file_put_contents($strLogFile, $str . "\n", FILE_APPEND);
    }

    public static function setFile($file) {
        $log = Util_CLog::getInstance();
        $log->file = $file;
    }

    public function getFile($depth) {
        if ($this->file) {
            return $this->file;
        } else {
            $trace = debug_backtrace();
            if ($depth >= count($trace)) {
                $depth = count($trace) - 1;
            }
            return basename($trace[$depth]['file']);
        }
    }

    public static function setLine($line) {
        $log = Util_CLog::getInstance();
        $log->line = $line;
    }

    public function getLine($depth) {
        if ($this->line) {
            return $this->line;
        } else {
            $trace = debug_backtrace();
            if ($depth >= count($trace)) {
                $depth = count($trace) - 1;
            }
            return basename($trace[$depth]['line']);
        }
    }

    private static function __logId() {
        $arr = gettimeofday();
        return ((($arr['sec'] * 100000 + $arr['usec'] / 10) & 0x7FFFFFFF) | 0x80000000);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>
