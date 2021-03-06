<?php

Class DiaryModel extends BasicModel {

    /**
     * @var DiaryModel
     */
    protected static $instances;
    protected $table = 'diary';
    protected $primaryKey = 'diary_id';
    protected $basicInfoKeys = array('diary_id', 'title', 'tags', 'pic_desc', 'thumbnail', 'visibility', 'user_id', 'date', 'date_ts');
    protected $extInfoKeys = array('diary_id', 'pic', 'content');

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
        $sqlUpdateDiary = $this->db->buildUpdateSqlStr($basicInfo, $this->table);
        $retCreateBasicInfo = $this->db->update($sqlUpdateDiary);
        if (!$retCreateBasicInfo) {
            throw new Exception("create diary basic info error " . var_export($basicInfo, true));
        }
        $extInfo['diary_id'] = $retCreateBasicInfo;
        $retCreateExtInfo = $this->createWithTimestamp($extInfo);
        if (!$retCreateExtInfo) {
            throw new Exception("create diary ext info error " . var_export($extInfo, true));
        }
        return true;
    }

    public function getDiaryById($diaryId) {
        if (!$diaryId || intval($diaryId) == 0) {
            throw new Exception_BadInput("bad input diary id");
        }
        return $this->getSingleDataByConditions(array('diary_id' => $diaryId));
    }

    public function getFirstDairy($userId) {
        if (!$userId || intval($userId) == 0) {
            throw new Exception_BadInput("bad input user id");
        }
        $sqlFormat = "SELECT * FROM $this->table WHERE user_id = %d ORDER BY date ASC LIMIT 1";
        return $this->db->queryFirstRow($sqlFormat, $userId);
    }
    
    public function getDataListByDateSectionAndConditions($columnKeyToValues, $startDate, $endDate) {
        $sqlFormat = "SELECT * FROM $this->table WHERE date >= %d AND date <= %d ";
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

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
