<?php

Class DiaryExtModel extends BasicModel {

    /**
     * @var DiaryExtModel
     */
    protected static $instances;
    protected $table = 'diary_ext';

    /**
     * @return DiaryExtModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new DiaryExtModel();
        }
        return self::$instances;
    }

    public function update($data) {
        if (!$data || !is_array($data) || !isset($data['diary_id']) || !$data['diary_id']) {
            throw new Exception_BadInput("bad input data for diary");
        }
        $sqlUpdateDiary = $this->db->buildUpdateSqlStr($data, $this->table);
        $sqlUpdateDiary .= " WHERE diary_id = {$data['diary_id']}";
        return $this->db->update($sqlUpdateDiary);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
