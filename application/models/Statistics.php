<?php

Class StatisticsModel extends BasicModel {

    /**
     * @var StatisticsModel 
     */
    protected static $instances;

    /**
     * @return StatisticsModel 
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new StatisticsModel();
        }
        return self::$instances;
    }

    protected function __construct() {
        
    }

    public function avgChargeListByKey($chargeList, $key = 'device_num') {
        if (!is_array($chargeList) || count($chargeList) == 0) {
            return 0;
        }
        $sumCharge = 0;
        $sumItemList = array();
        foreach ($chargeList as $charge) {

            if (!isset($charge[$key])) {
                continue;
            }
            $sumCharge += $charge['total_fee'];
            $sumItemList[$charge[$key]] = 1;
        }
        return round($sumCharge / count($sumItemList), 2);
    }

    public function totalChargeListByKey($chargeList, $key = 'device_num') {
        if (!is_array($chargeList) || count($chargeList) == 0) {
            return 0;
        }
        $sumCharge = 0;
        foreach ($chargeList as $charge) {

            if (!isset($charge[$key])) {
                continue;
            }
            $sumCharge += $charge['total_fee'];
        }
        return round($sumCharge, 2);
    }

    public function CountNumByKey($chargeList, $key = 'device_num') {
        if (!is_array($chargeList) || count($chargeList) == 0) {
            return 0;
        }
        $sumItemList = array();
        foreach ($chargeList as $charge) {

            if (!isset($charge[$key])) {
                continue;
            }
            $sumItemList[$charge[$key]] = 1;
        }
        return count($sumItemList, 2);
    }

    public function buildSectionChargeAvg($chargeList, $item = 'device_num') {
        $result = array();
        $statisticsChargeList = array();
        foreach ($chargeList as $charge) {
            $statisticsChargeList[$charge['date']]['avgItemList'][$charge[$item]] = 1;
            if (!isset($statisticsChargeList[$charge['date']]['total_fee'])) {
                $statisticsChargeList[$charge['date']]['total_fee'] = $charge['total_fee'];
            } else {
                $statisticsChargeList[$charge['date']]['total_fee'] += $charge['total_fee'];
            }
        }
        foreach ($statisticsChargeList as $key => $value) {
            if (!isset($value['total_fee']) || !isset($value['avgItemList'])) {
                continue;
            }
            $result[$key] = round($value['total_fee'] / count($value['avgItemList']), 2);
        }
        return $result;
    }

    public function buildSectionChargeAvgList($chargeList, $item = 'device_num') {
        $result = array();
        $statisticsChargeList = array();
        foreach ($chargeList as $charge) {
            if ($charge['status'] != 0) {
                continue;
            }
            $statisticsChargeList[$charge['date']]['avgItemList'][$charge[$item]] = 1;
            if (!isset($statisticsChargeList[$charge['date']]['total_fee'])) {
                $statisticsChargeList[$charge['date']]['total_fee'] = $charge['total_fee'];
            } else {
                $statisticsChargeList[$charge['date']]['total_fee'] += $charge['total_fee'];
            }
        }
        foreach ($statisticsChargeList as $key => $value) {
            $data = array();
            if (!isset($value['total_fee']) || !isset($value['avgItemList'])) {
                continue;
            }
            $data['total_fee'] = round($value['total_fee'] / count($value['avgItemList']), 2);
            $data['date'] = $key;
            $result[$key] = $data;
        }
        return $result;
    }

    public function isTop($chargList, $deviceNum, $topCnt = TOTAL_FEE_TOP_LIMIT) {
        $deviceNumToTotalFee = array();
        foreach ($chargList as $charge) {
            $deviceNumToTotalFee[$charge['device_num']] = $charge['total_fee'];
        }
        arsort($deviceNumToTotalFee);
        array_splice($deviceNumToTotalFee, $topCnt);
        return isset($deviceNumToTotalFee[$deviceNum]);
    }

    public function buildStaticsInfoForImg($companyId, $chargeList) {
        $statisticsInfo = array();
        if (!$companyId) {
            throw new Exception_BadInput("Bad input company Id");
        }
        $companyInfo = CompanyinfoModel::getInstance()->getCompanyInfoByCid($companyId);
        if (isset($companyInfo['show_allocation']) && intval($companyInfo['show_allocation']) > 0) {
            $statisticsInfo['allocation'] = $companyInfo['show_allocation'];
        }
        if (isset($companyInfo['show_average']) && intval($companyInfo['show_average']) > 0) {
            $statisticsInfo['co_avg'] = $this->buildSectionChargeAvg($chargeList);
        }
        return $statisticsInfo;
    }

    public function buildCompanyStatisticsInfo($companyId, $date = '', $isTrial = false) {
        $key = McKeyModel::getInstance()->forCompanyInfo('statistics', $companyId, $date);
        return MemcachedModel::getInstance()->getMCDataMagic($key, array($this, 'doBuildCompanyStatisticsInfo'), MEMCACHE_LIFETIME, $companyId, $date, $isTrial);
    }

    public function doBuildCompanyStatisticsInfo($companyId, $date = '', $isTrial = false) {
        if (!$date) {
            $date = ChargeModel::getInstance()->getLatestDateByCompanyId($companyId);
        }
        $companyInfo = CompanyinfoModel::getInstance()->getCompanyInfoByCid($companyId);
        if ($isTrial) {
            $chargeList = ChargeModel::getInstance()->getCompanyTopChargeList($companyId, 0, TRIAL_VERSION_USER_LIMIT);
            foreach ($chargeList as $value) {
                $totalCharge = $totalCharge + $value['total_fee'];
            }
            $avgEmployeeCharge = round($totalCharge / TRIAL_VERSION_USER_LIMIT, 2);
        } else {
            $conditions = array('date' => $date, 'co_id' => $companyId);
            $chargeList = ChargeModel::getInstance()->sortByConditions($conditions);
            $chargeStatistics = ChargeStatisticsModel::getInstance()->getChargeStatisticsTotalBycoIdAndDate($companyId, $date);
            $avgEmployeeCharge = $chargeStatistics['avgEmployeeCharge'];
        }
        $companyInfo['total_fee_top_list'] = $this->getTotalFeeTopList($chargeList);
        $companyInfo['co_avg'] = $avgEmployeeCharge;
        return $companyInfo;
    }

    public function deleteCompanyStatisticsCacheByDateListAndCompanyId($dateList, $companyId) {
        if (!is_array($dateList) || count($dateList) == 0 || !$companyId) {
            throw new Exception_BadInput("Bad input company Id or dateList"); 
        }
        foreach ($dateList as $date) {
            $key = McKeyModel::getInstance()->forCompanyInfo('statistics', $companyId, $date);
            MemcachedModel::getInstance()->delete($key);
        }
        return true;
    }

    public function getTotalFeeTopList($chargList, $topCnt = TOTAL_FEE_TOP_LIMIT) {
        $deviceNumToTotalFee = array();
        foreach ($chargList as $charge) {
            $deviceNumToTotalFee[$charge['device_num']] = $charge['total_fee'];
        }
        arsort($deviceNumToTotalFee);
        array_splice($deviceNumToTotalFee, $topCnt);
        return $deviceNumToTotalFee;
    }
    public function buildChargeSummary($chargeList, $departmentId, $date) {
        if(!is_array($chargeList) || count($chargeList) == 0){
            return false;
        }
        $deviceList = array();
        $totalFee = 0;
        foreach ($chargeList as $charge){
            $totalFee += $charge['total_fee'];
            $deviceList[$charge['device_num']] = 1;
        }
        if ($totalFee != 0 && count($deviceList) != 0) {
            $chargeStatisticsTotalFee['totalCharge'] = $totalFee;
            $chargeStatisticsTotalFee['totelDeviceNum'] = count($deviceList);
            $chargeStatisticsTotalFee['avgEmployeeCharge'] = round($totalFee / $chargeStatisticsTotalFee['totelDeviceNum'], 2);
        } else {
            $chargeStatisticsTotalFee['avgEmployeeCharge'] = 0;
            $chargeStatisticsTotalFee['totelDeviceNum'] = 0;
        }

        $totalAdjustmentFee = AdjustmentFeeModel::getInstance()->getTotalAdjustmentFeeByDepartmentIdAndDate($departmentId, $date);
        if (isset($totalAdjustmentFee['item_fee']) && $totalAdjustmentFee['item_fee']) {
            $chargeStatisticsTotalFee['totalCharge'] += $totalAdjustmentFee['item_fee'];
        }

        return $chargeStatisticsTotalFee;
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
