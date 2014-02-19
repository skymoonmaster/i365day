<?php

Class UserDiaryModel extends BasicModel {

    /**
     * @var UserDiaryModel
     */
    protected static $instances;
    protected $table = 'user_diary';
    protected $primaryKey = 'user_diary_id';

    const DEFAULT_LIMIT = 8;
    /**
     * @return UserDiaryModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new UserDiaryModel();
        }
        return self::$instances;
    }
    
    public function replace($inputUserDiary){
        if(!isset($inputUserDiary['diary_id']) || intval($inputUserDiary['diary_id']) == 0
                ||!isset($inputUserDiary['user_id']) || intval($inputUserDiary['user_id']) == 0){
            throw new Exception_BadInput('bad input user diary');
        }
        $userDiaryFromDb = $this->getSingleDataByConditions(array('user_id' => $inputUserDiary['user_id'], 'diary_id' => $inputUserDiary['diary_id']));
        if(!$userDiaryFromDb){
            return $this->createWithTimestamp($inputUserDiary);
        }else{
            $inputUserDiary[$this->primaryKey] = $userDiaryFromDb[$this->primaryKey];
            return $this->update($inputUserDiary);
        }
    }

    public function getListExtByUserId($userId, $offset, $limit = self::DEFAULT_LIMIT){
        $sqlFormat = "SELECT * FROM $this->table 
            LEFT JOIN diary ON $this->table.diary_id = diary.diary_id
            LEFT JOIN user ON $this->table.user_id = user.user_id
            WHERE $this->table.user_id = %d LIMIT {$offset}, {$limit}" ;
        return $this->db->queryAllRows($sqlFormat, $userId);
    }

    public function getLikeDiaryCount($userId) {
        $sqlFormat = "SELECT COUNT(*) AS count FROM $this->table
            LEFT JOIN diary ON $this->table.diary_id = diary.diary_id
            LEFT JOIN user ON $this->table.user_id = user.user_id
            WHERE $this->table.user_id = %d";

        $row = $this->db->queryFirstRow($sqlFormat, $userId);

        return $row['count'];
    }

    public function isRelated($userId, $diaryId){
        $sqlFormat = "SELECT * FROM $this->table WHERE $this->table.user_id = %d AND $this->table.diary_id = %d";
        $ret = $this->db->queryFirstRow($sqlFormat, $userId, $diaryId);
        return $ret ? true : false;
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
