<?php

/**
 * 
 * @package	script
 * @version	$Revision: 1.0 $
 * */
class Script_BasicDaemon {

    protected $daemonName = '';

    public function run() {
        $db = DB_ProxyWrapper::getInstance(COST_PLATFORM);
        $loop = 0;
        do {
            $startTime = microtime(true) * 1000;
            try {
                $ret = $this->doMission($db, $loop);
                if($ret){
                    $loop++;
                } else {
                    sleep(10);
                    $loop = 0;
                }
            } catch (Exception_BadInput $e) {
                Util_CLog::setFile(basename($e->getFile()));
                Util_CLog::setLine($e->getLine());
                Util_CLog::warning($e->getMessage());
                $loop++;
            } catch (Exception_Login $e) {
                Util_CLog::setFile(basename($e->getFile()));
                Util_CLog::setLine($e->getLine());
                Util_CLog::warning($e->getMessage());
                $loop++;
            } catch (Exception $e) {
                Util_CLog::setFile(basename($e->getFile()));
                Util_CLog::setLine($e->getLine());
                Util_CLog::warning($e->getMessage());
                $loop++;
            }
            $logFormat = "%s mission complete with offset [%d] cost [%d]";
            $costTime = $loop == 0 ? microtime(true) * 1000 - $startTime - 10000 : microtime(true) * 1000 - $startTime;
            Util_CLog::notice(sprintf($logFormat, $this->daemonName, $loop * FETCH_LIMIT_PER_TIME, $costTime));
        } while (1);
    }

    public function doMission($db, $loop) {
        
    }
    
}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>
