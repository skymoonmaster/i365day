<?php

Class UserModel extends BasicModel {

    public static $cnt = 0;

    /**
     * @var UserModel
     */
    protected static $instances;

    /**
     * @return UserModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new UserModel();
        }
        return self::$instances;
    }

    public function createUser($user) {
        if (!is_array($user) || count($user) == 0) {
            return FALSE;
        }
        return $this->db->insert($user, 'user');
    }

    public function getUserByName($uname) {
        return $this->getUserInfo('user_name', Util_EncryptDecrypt::getInstance()->encryptdecrypt($uname, 'ENCODE', Util_EncryptDecrypt::KEY, 0));
    }

    public function getUserByEmail($userEmail) {
        return $this->getUserInfo('user_email', Util_EncryptDecrypt::getInstance()->encryptdecrypt($userEmail, 'ENCODE', Util_EncryptDecrypt::KEY, 0));
    }

    public function getUserInfoByEmployeeId($employeeId) {
        return $this->getUserInfo('employee_id', $employeeId);
    }

    public function getUserInfoByUserId($userId) {
        return $this->getUserInfo('user_id', $userId);
    }

    public function getUserByUnameAndRealName($uname, $userRealName) {
        $user = array();
        if (!$userRealName || strlen($userRealName) == 0) {
            throw new Exception_BadInput("User real name can not be empty");
        }
        if (!$uname || strlen($uname) == 0) {
            throw new Exception_BadInput("User name can not be empty");
        }
        $unameEncrypt = Util_EncryptDecrypt::getInstance()->encryptdecrypt($uname, 'ENCODE', Util_EncryptDecrypt::KEY, 0);
        $userRealNameEncrypt = Util_EncryptDecrypt::getInstance()->encryptdecrypt($userRealName, 'ENCODE', Util_EncryptDecrypt::KEY, 0);
        $sqlFormat = "SELECT * FROM user WHERE  user_name = %s and user_real_name = %s";
        $user = $this->db->queryFirstRow($sqlFormat, $unameEncrypt, $userRealNameEncrypt);
        if (!is_array($user) || count($user) == 0) {
            Util_CLog::selflog('db', "can not find user by user real name $userRealName uname $uname");
        }
        return $user;
    }

    public function getUserDeviceNumList($uid) {
        $deviceNumList = array();
        if (!$uid || intval($uid) == 0) {
            throw new Exception_BadInput("User id can not be empty");
        }
        $sqlFormat = "SELECT * FROM user_device WHERE user_id = %d";
        $userDeviceList = $this->db->queryAllRows($sqlFormat, $uid);
        if (!is_array($userDeviceList) || count($userDeviceList) == 0) {
            Util_CLog::selflog('db', "can not find devices by user uid " . $uid);
        }
        foreach ($userDeviceList as $userDevice) {
            $deviceNumList[] = $userDevice['device_num'];
        }
        return $deviceNumList;
    }

    public function getUserListByDepartmentId($departmentId) {
        $userList = array();
        if (!$departmentId || intval($departmentId) == 0) {
            throw new Exception_BadInput("Department id can not be empty");
        }
        $sqlFormat = "SELECT * FROM user WHERE  department_id = %d";
        $userList = $this->db->queryAllRows($sqlFormat, $departmentId);
        if (!is_array($userList) || count($userList) == 0) {
            Util_CLog::selflog('db', "can not find user by department id $departmentId");
        }
        return $userList;
    }

    public function getUserInfoByDeviceNumAndCompanyId($deviceNum, $companyId) {
        $user = array();
        if (!$deviceNum || strlen($deviceNum) == 0) {
            throw new Exception_BadInput("Device num can not be empty");
        }
        $deviceNumEncypt = Util_EncryptDecrypt::getInstance()->encryptdecrypt($deviceNum, 'ENCODE', Util_EncryptDecrypt::KEY, 0);
        $sqlFormat = "SELECT user.* FROM user LEFT JOIN user_device on user.user_id = user_device.user_id
            WHERE  user_device.device_num = %s and user_device.co_id = %d";
        $user = $this->db->queryFirstRow($sqlFormat, $deviceNumEncypt, $companyId);
        if (!is_array($user) || count($user) == 0) {
            Util_CLog::selflog('db', "can not find user by device_num $deviceNum");
        }
        return $user;
    }

    public function replaceUserInfo($userInfo) {
        if (!is_array($userInfo) || count($userInfo) == 0) {
            throw new Exception_BadInput("User info can not be empty");
        }
        //REPLACE is not that commom use
        $searchSqlFormat = "SELECT * FROM user WHERE user_id = %s";
        $result = $this->db->queryFirstRow($searchSqlFormat, $userInfo['user_id']);
        $userInfo['is_complete'] = $this->isUserInfoComplete($userInfo) ? 1 : 0;
        if (!$result) {
            unset($userInfo['user_id']);
            $this->db->insert($userInfo, 'user');
            return $this->db->getLastInsertID();
        } else {
            $condition = "WHERE user_id ='" . $this->db->realEscapeString($userInfo['user_id']) . "'";
            $updateSql = $this->db->buildUpdateSqlStr($userInfo, 'user') . " $condition";
            self::$cnt++;
            return $this->db->update($updateSql);
        }
    }

    //replace userInfo first select email
    public function replaceUserInfoByemail($userInfo) {
        if (!is_array($userInfo) || count($userInfo) == 0) {
            throw new Exception_BadInput("User info can not be empty");
        }
        //REPLACE is not that commom use
        $searchSqlFormat = "SELECT * FROM user WHERE user_email = %s";
        $userEmailEncypt = Util_EncryptDecrypt::getInstance()->encryptdecrypt($userInfo['user_email'], 'ENCODE', Util_EncryptDecrypt::KEY, 0);
        $result = $this->db->queryFirstRow($searchSqlFormat, $userEmailEncypt);
        if (!$result) {
            unset($userInfo['user_id']);
            $this->db->insert($userInfo, 'user');
            return $this->db->getLastInsertID();
        } else {
            $condition = "WHERE user_email ='" . $this->db->realEscapeString($userEmailEncypt) . "'";
            $updateSql = $this->db->buildUpdateSqlStr($userInfo, 'user') . " $condition";
            self::$cnt++;
            return $this->db->update($updateSql);
        }
    }

    public function isUserInfoValidated($companyId, $date = '', $chargeAccountId = '') {
        $unValidatedUserAmount = $this->getUnValidatedUserAmount($companyId, $date, $chargeAccountId);
        return $unValidatedUserAmount == 0;
    }

    public function getUnValidatedUserAmount($companyId, $date = '', $chargeAccountId = '') {
        if (!$companyId || !intval($companyId)) {
            throw new Exception_BadInput("Company id can not be empty");
        }
        if (!$date) {
            $date = ChargeModel::getInstance()->getLatestDateByCompanyId($companyId);
        }
        $license = LicenseModel::getInstance()->getLincenseInfoByCompanyId($companyId);
        $isTrial = isset($license['license_level']) && $license['license_level'] > 0 ? false : true;
        if ($isTrial) {
            $setupedUsersAmount = ChargeModel::getInstance()->getSetupUserAmount($companyId, $date, $chargeAccountId, $isTrial);
            $companyUserAmount = ChargeModel::getInstance()->getChargeUserAmount($companyId, $date, $chargeAccountId, $isTrial);
            return ( intval($companyUserAmount) - intval($setupedUsersAmount));
        } else {
            return ChargeModel::getInstance()->getUncompletedChargeUserAmount($companyId, $date, $chargeAccountId);
        }
    }

    public function getUserInfo($columKey, $columValue) {
        $user = array();
        if (!$columValue || strlen($columValue) == 0) {
            throw new Exception_BadInput("$columKey info can not be empty");
        }
        $sqlFormat = "SELECT * FROM user WHERE $columKey = %s";
        $user = $this->db->queryFirstRow($sqlFormat, $columValue);
        if (!is_array($user) || count($user) == 0) {
            Util_CLog::selflog('db', "can not find user by user $columKey " . $columValue);
        }
        return $user;
    }

    //insert user 
    public function insertUser($fieldInfo) {
        if (!is_array($fieldInfo) || count($fieldInfo) == 0) {
            return FALSE;
        }
        $this->db->insert($fieldInfo, 'user');
        return $this->db->getLastInsertID();
    }

    //check user email coid
    public function checkUserByCoidAndEmail($userEmail, $coId) {
        if (!isset($coId) || !isset($userEmail)) {
            return FALSE;
        }
        $sqlFormat = "SELECT * FROM user WHERE  co_id = %d and user_email = %s";
        $userList = $this->db->queryAllRows($sqlFormat, $coId, Util_EncryptDecrypt::getInstance()->encryptdecrypt($userEmail, 'ENCODE', Util_EncryptDecrypt::KEY, 0));
        return $userList;
    }

    public function isUserCompleted($userId, $companyId) {
        $userInfo = $this->getUserInfoByUserId($userId);
        if (!$userInfo) {
            return false;
        }
        list($requiredSortItem, $optionalSortItem) = SortItemModel::getInstance()->getAssociateSortItemsByCompanyId($companyId);
        $skipKeys = array(
            'user_real_name' => 1,
            'user_name' => 1,
            'user_status' => 1,
            'market_id' => 1,
            'employee_id' => 1,
            'is_employee' => 1
        );
        if ($optionalSortItem) {
            unset($skipKeys['market_id']);
        }
        foreach ($userInfo as $key => $value) {
            if (!isset($skipKeys[$key]) && !$value) {
                return false;
            }
        }
        return true;
    }

    public function deleteUserByUserid($userId) {
        if (!$userId) {
            throw new Exception_BadInput("user id can not be empty");
        }
        $sqlFormat = "delete FROM user";
        $sqlFormat = $sqlFormat . "  WHERE  `user_id` =" . intval($userId);
        return $this->db->update($sqlFormat);
    }

    public function getDemoUserList($companyId) {
        $sqlFormat = "SELECT * FROM user WHERE  co_id = %d and user_status = " . DemoModel::DEMO_STATUS;
        $userList = $this->db->queryAllRows($sqlFormat, $companyId);
        if (!$userList) {
            Util_CLog::selflog('db', "can not find user by company id [$companyId]");
            return array();
        }
        return $userList;
    }

    public function isDemoDataInited($companyId) {
        $userList = $this->getDemoUserList($companyId);
        if ($userList && is_array($userList) && count($userList) > 0) {
            return true;
        }
        return false;
    }
    private function isUserInfoComplete($userInfo){
        list($requiredSortItem, $optionalSortItem) = SortItemModel::getInstance()->getAssociateSortItemsByCompanyId($userInfo['co_id']);
        if(!isset($userInfo['user_first_name']) || !$userInfo['user_first_name']){
            return false;
        }
        if(!isset($userInfo['user_last_name']) || !$userInfo['user_last_name']){
            return false;
        }
        if(!isset($userInfo['user_email']) || !$userInfo['user_email']){
            return false;
        }
        if(!isset($userInfo['department_id']) || !$userInfo['department_id']){
            return false;
        }
        if (isset($optionalSortItem['sort_item_name']) && $optionalSortItem['sort_item_name'] 
                && (!isset($userInfo['market_id']) || !$userInfo['market_id'])) {
           return false;
        } 
        return true;
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
