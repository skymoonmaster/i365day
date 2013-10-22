<?php

Class DiaryExtModel extends BasicModel {

    /**
     * @var DiaryExtModel
     */
    protected static $instances;
    protected $table = 'diary_ext';
    protected $primaryKey = 'diary_ext_id';

    /**
     * @return DiaryExtModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new DiaryExtModel();
        }
        return self::$instances;
    }
    
    public function getDiaryExtByDiaryId($diaryId) {
        if (!$diaryId || intval($diaryId) == 0) {
            throw new Exception_BadInput("bad input diary id");
        }
        return $this->getSingleDataByConditions(array('diary_id' => $diaryId));
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
