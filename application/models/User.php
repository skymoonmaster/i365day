<?php

Class UserModel extends BasicModel {

    /**
     * @var UserModel
     */
    protected static $instances;
    
    protected $table = 'user';

    /**
     * @return UserModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new UserModel();
        }
        return self::$instances;
    }

    public function getUserInfoById($userId) {
        return $this->getUserInfo('user_id', $userId);
    }

    public function replaceUserInfo($userInfo) {
        if (!is_array($userInfo) || count($userInfo) == 0) {
            throw new Exception_BadInput("User info can not be empty");
        }
        //REPLACE is not that commom use
        $searchSqlFormat = "SELECT * FROM $this->table WHERE user_id = %d";
        $result = $this->db->queryFirstRow($searchSqlFormat, $userInfo['user_id']);
        if (!$result) {
            unset($userInfo['user_id']);
            $this->db->insert($userInfo, $this->table);
            return $this->db->getLastInsertID();
        } else {
            $condition = "WHERE user_id ='" . $this->db->realEscapeString($userInfo['user_id']) . "'";
            $updateSql = $this->db->buildUpdateSqlStr($userInfo, $this->table) . " $condition";
            return $this->db->update($updateSql);
        }
    }

    public function getUserInfoByConditions(array $conditions) {
        if (empty($conditions)) {
            throw new Exception_BadInput("empty params");
        }

        return $this->getSingleDataByConditions($conditions);
    }

    public function getUserInfo($columKey, $columValue) {
        $user = array();
        if (!$columValue || strlen($columValue) == 0) {
            throw new Exception_BadInput("$columKey info can not be empty");
        }
        $sqlFormat = "SELECT * FROM $this->table WHERE $columKey = %s";
        $user = $this->db->queryFirstRow($sqlFormat, $columValue);
        if (!is_array($user) || count($user) == 0) {
            Util_CLog::selflog('db', "can not find user by user $columKey " . $columValue);
        }
        return $user;
    }

    public function getUserInfos(array $uids) {
        if (empty($uids)) {
            throw new Exception_BadInput("Empty params error");
        }

        $sql = "SELECT * FROM `user` WHERE `user_id` IN (";
        $sql .= implode(',', $uids);
        $sql .= ");";

        $rows = $this->db->queryAllRows($sql);

        if (empty($rows)) {
            return array();
        }

        $userInfos = array();
        foreach($rows as $rows) {
            $userInfos[$rows['user_id']] = $row;
        }

        return $userInfos;
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
