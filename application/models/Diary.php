<?php

Class DiaryModel extends BasicModel {

    /**
     * @var DiaryModel
     */
    protected static $instances;
    protected $table = 'diary';
    protected $primaryKey = 'diary_id';
    protected $basicInfoKeys = array('diary_id', 'title', 'type', 'tags', 'pic_desc', 'thumbnail', 'visibility', 'user_id', 'date', 'date_ts', 'is_admin' , 'status');
    protected $extInfoKeys = array('diary_ext_id', 'diary_id', 'pic', 'content');

    /**
     * @return DiaryModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new DiaryModel();
        }
        return self::$instances;
    }

    public function replaceDiary($data) {
        if (!$data || !is_array($data)) {
            throw new Exception_BadInput("bad input data for diary");
        }
        if (!isset($data[$this->primaryKey]) || intval($data[$this->primaryKey]) == 0) {
            return $this->createDiary($data);
        }
        $diaryFromDb = $this->getDiaryById($data[$this->primaryKey]);
        if (!$diaryFromDb) {
            return $this->createDiary($data);
        }
        return $this->updateDiary($data);
    }

    public function createDiary($data) {
        if (!$data || !is_array($data)) {
            throw new Exception_BadInput("bad input data for diary");
        }
        //separate the basic info and ext info for diary
        foreach ($data as $key => $value) {
            if ($key && in_array($key, $this->basicInfoKeys)) {
                $basicInfo[$key] = $value;
            } else if ($key && in_array($key, $this->extInfoKeys)) {
                $extInfo[$key] = $value;
            }
        }
        $retCreateBasicInfo = $this->createWithTimestamp($basicInfo);
        if (!$retCreateBasicInfo) {
            throw new Exception("create diary basic info error " . var_export($basicInfo, true));
        }
        $extInfo['diary_id'] = $retCreateBasicInfo;
        $retCreateExtInfo = DiaryExtModel::getInstance()->create($extInfo);
        if (!$retCreateExtInfo) {
            throw new Exception("create diary ext info error " . var_export($extInfo, true));
        }
        return $retCreateBasicInfo;
    }

    public function updateDiary($data) {
        if (!$data || !is_array($data) || !isset($data['diary_id']) || !$data['diary_id']) {
            throw new Exception_BadInput("bad input data for diary");
        }
        //separate the basic info and ext info for diary
        foreach ($data as $key => $value) {
            if ($key && in_array($key, $this->basicInfoKeys)) {
                $basicInfo[$key] = $value;
            } else if ($key && in_array($key, $this->extInfoKeys)) {
                $extInfo[$key] = $value;
            }
        }
        $sqlUpdateDiary = $this->db->buildUpdateSqlStr($basicInfo, $this->table, $data['diary_id']);
        $sqlUpdateDiary .= "WHERE diary_id = " . intval($data['diary_id']);
        $retUpdateBasicInfo = $this->db->update($sqlUpdateDiary);
        if (!$retUpdateBasicInfo) {
            throw new Exception("update diary basic info error " . var_export($basicInfo, true));
        }
        $retUpdateExtInfo = DiaryExtModel::getInstance()->update($extInfo);
        if (!$retUpdateExtInfo) {
            throw new Exception("update diary ext info error " . var_export($extInfo, true));
        }
        return true;
    }
    public function delDiaryById($diaryId) {
        if (!$diaryId) {
            throw new Exception_BadInput("bad input diaryId for diary");
        }
        $diaryInfo = array('diary_id' => $diaryId, 'status' => DiaryModel::$statusDel);
        $sqlUpdateDiary = $this->db->buildUpdateSqlStr($diaryInfo, $this->table, $diaryId);
        $sqlUpdateDiary .= "WHERE diary_id = " . intval($diaryId);
        $retUpdateBasicInfo = $this->db->update($sqlUpdateDiary);
        if (!$retUpdateBasicInfo) {
            throw new Exception("delete diary error by id " . $diaryId);
        }
        return true;
    }

    public function getDiaryById($diaryId) {
        if (!$diaryId || intval($diaryId) == 0) {
            throw new Exception_BadInput("bad input diary id");
        }
        return $this->getSingleDataByConditions(array('diary_id' => $diaryId, 'status' => self::$statusNormal));
    }

    public function getFirstDairy($userId) {
        if (!$userId || intval($userId) == 0) {
            throw new Exception_BadInput("bad input user id");
        }
        $sqlFormat = "SELECT * FROM $this->table WHERE user_id = %d ORDER BY date ASC LIMIT 1";
        return $this->db->queryFirstRow($sqlFormat, $userId);
    }
    
    public function getDataListByDateSectionAndConditions($columnKeyToValues, $startDate, $endDate) {
        $sqlFormat = "SELECT * FROM $this->table WHERE status=0 AND date >= %d AND date <= %d ";
        foreach ($columnKeyToValues as $key => $value) {
            if (!$key) {
                continue;
            }
            $sqlFormat .= " AND $key = '" . $this->db->realEscapeString($value) . "'";
        }
        return $this->db->queryAllRows($sqlFormat, $startDate, $endDate);
    }
    
    public function addFavNumById($diaryId){
        if (!$diaryId || intval($diaryId) == 0) {
            throw new Exception_BadInput("bad input diary id");
        }
        $sqlFormat = "UPDATE $this->table SET fav_num = fav_num + 1 WHERE diary_id = %d";
        $sqlStr = $this->db->buildSqlStr($sqlFormat, $diaryId);
        return $this->db->update($sqlStr);
    }
    
    public function addCommentNumById($diaryId){
        if (!$diaryId || intval($diaryId) == 0) {
            throw new Exception_BadInput("bad input diary id");
        }
        $sqlFormat = "UPDATE $this->table SET comment_num = comment_num + 1 WHERE diary_id = %d";
        $sqlStr = $this->db->buildSqlStr($sqlFormat, $diaryId);
        return $this->db->update($sqlStr);
    }

    public function getDiaryAmountByUid($userId){
        if (!$userId || intval($userId) == 0) {
            throw new Exception_BadInput("bad input user id");
        }
        return $this->getAmountByConditions(array('user_id' => $userId, 'status' => self::$statusNormal));
    }

    public function getDiaryListExtByConditions($columnKeyToValues, $order = ''){
        $sqlFormat = "SELECT * FROM $this->table"
                . " LEFT JOIN diary_ext ON $this->table.diary_id = diary_ext.diary_id"
                . " LEFT JOIN user ON $this->table.user_id = user.user_id"
                . " WHERE $this->table.status=0 ";
        foreach ($columnKeyToValues as $key => $value) {
            if (!$key) {
                continue;
            }
            $sqlFormat .= " AND $key = '" . $this->db->realEscapeString($value) . "'";
        }
        if($order){
            $sqlFormat .= $order;
        }
        return $this->db->queryAllRows($sqlFormat);
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
