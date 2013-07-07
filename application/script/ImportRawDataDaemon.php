<?php

/**
 * 
 * @package	script
 * @version	$Revision: 1.0 $
 * */
class Script_ImportRawDataDaemon extends Script_BasicDaemon {

    protected $daemonName = 'ImportRawData';

    public function doMission($db, $loop) {
        $sqlFormat = "SELECT * FROM import_raw_data_mission WHERE status = 0  LIMIT %d, " . IMPORT_FETCH_LIMIT_PER_TIME;
        $missionList = $db->queryAllRows($sqlFormat, $loop * IMPORT_FETCH_LIMIT_PER_TIME);
        if (!is_array($missionList) || count($missionList) == 0) {
            return false;
        }
        return $this->batchImportRawData($missionList);
    }

    private function batchImportRawData($missionList) {
        if (!is_array($missionList) || count($missionList) == 0) {
            Util_CLog::notice("bad input mission list");
            return true;
        }
        foreach ($missionList as $missionInfo) {
            $startTime = microtime(true) * 1000;
            if (!is_array($missionInfo) || count($missionInfo) == 0) {
                continue;
            }
            if (strlen($missionInfo['import_raw_data_mission_filename']) == 0 || !is_file($missionInfo['import_raw_data_mission_filename'])) {
                Util_CLog::notice("can not find the file {$missionInfo['import_raw_data_mission_filename']}");
                continue;
            }
            $params = json_decode($missionInfo['import_raw_data_mission_params'], true);
            $chargeModel = BasicChargeModel::getChargeModelInstance($missionInfo['import_raw_data_mission_type']);
            $chargeModel->setIsDemoData($params['is_demo']);
            $ret = $chargeModel->processRawData($missionInfo['import_raw_data_mission_filename'], $missionInfo['date'], $missionInfo['co_id']);
            if (!$ret) {
                $missionInfo['status'] = -1;
            } else {
                $missionInfo['status'] = 1;
                $maxChargeDate = ChargeModel::getInstance()->getLatestDateByCompanyId($missionInfo['co_id']);
                if ($maxChargeDate == $missionInfo['date']) {
                    $this->refreshDeviceStatus($missionInfo['co_id'], $chargeModel->getChargeAccountIdList());
                }
            }
            $missionInfo['cost_time'] = microtime(true) * 1000 - $startTime;
            $retUpdateMission = ImportRawDataMissionModel::getInstance()->updateMissionInfo($missionInfo);


            $deviceList = DeviceModel::getInstance()->getDeactivedDeviceByCoid($missionInfo['co_id']);

            if (is_array($deviceList) && count($deviceList) > 0) {
                foreach ($deviceList as $value) {
                    $value['date'] = $missionInfo['date'];
                    $value['co_id'] = $missionInfo['co_id'];
                    $chargeInfo = ChargeModel::getInstance()->getChargeByConditions($value);
                    if (is_array($chargeInfo) && count($chargeInfo) > 0) {
                        $chargeInfo['status'] = -2;
                        ChargeModel::getInstance()->updateCharge($chargeInfo);
                    }
                }
            }

            $retFreshChargeStatistics = ChargeStatisticsModel::getInstance()->freshStatistics($missionInfo['co_id'], $missionInfo['date'], $params['is_demo']);
            $reportStatistics = ReportStatisticsModel::getInstance()->getReportByCompanyIdAndDate($missionInfo['co_id'], $missionInfo['date']);
            $retRefreshReportStatistics = ReportStatisticsModel::getInstance()->refreshAllStatus($reportStatistics);
            
            if (!$retUpdateMission || !$retFreshChargeStatistics || !$retRefreshReportStatistics) {
                throw new Exception("Update statistics error");
            }
        }

        return true;
    }

    private function refreshDeviceStatus($companyId, $chargeAccountIdList) {
        $chargeAccountIdList = array_map("intval", $chargeAccountIdList);
        $deviceList = DeviceModel::getInstance()->getDeviceListByCompanyIdAndChargeAccountIds($companyId, $chargeAccountIdList);
        $date = ChargeModel::getInstance()->getLatestDateByCompanyId($companyId);
        if (!is_array($deviceList) || count($deviceList) == 0) {
            return true;
        }
        foreach ($deviceList as $device) {
            $conditions = array('device_num' => $device['device_num'], 'date' => $date, 'co_id' => $companyId);
            $chargeInfo = ChargeModel::getInstance()->sortByConditions($conditions);
            if (is_array($chargeInfo) && count($chargeInfo) > 0 && $device['is_used'] == 0) {
                continue;
            }
            if (is_array($chargeInfo) && count($chargeInfo) > 0 && $device['is_used'] == DeviceModel::STATUS_DEACTIVE) {
                $device['is_used'] = 0;
            } else {
                $device['is_used'] = DeviceModel::STATUS_DEACTIVE;
            }
            $retUpdateDeviceStatus = DeviceModel::getInstance()->replaceDeviceInfo($device);
            if (!$retUpdateDeviceStatus) {
                throw new Exception("Update device error " . var_export($device, true));
            }
        }
        return true;
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>
