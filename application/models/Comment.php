<?php

Class CommentModel extends BasicModel {

    /**
     * @var CommentModel
     */
    protected static $instances;
    protected $table = 'comment';
    protected $primaryKey = 'comment_id';

    /**
     * @return CommentModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new CommentModel();
        }
        return self::$instances;
    }

    public function getCommentByDiaryId($diaryId) {
        if (!$diaryId || intval($diaryId) == 0) {
            throw new Exception_BadInput("bad input diary id");
        }
        $sqlFormat = "SELECT * FROM $this->table WHERE status = 0 AND diary_id = %d ORDER BY create_time DESC ";
        return $this->db->queryAllRows($sqlFormat, $diaryId);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
