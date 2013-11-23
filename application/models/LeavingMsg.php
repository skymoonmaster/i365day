<?php

Class LeavingMsgModel extends BasicModel {

    /**
     * @var LeavingMsgModel
     */
    protected static $instances;
    protected $table = 'leaving_msg';
    protected $primaryKey = 'leaving_msg_id';

    /**
     * @return LeavingMsgModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new LeavingMsgModel();
        }
        return self::$instances;
    }

    public function getLeavingMsgByOwnerId($hostId) {
        if (!$hostId || intval($hostId) == 0) {
            throw new Exception_BadInput("bad input host id");
        }
        $sqlFormat = "SELECT * FROM $this->table WHERE host_id = %d ORDER BY create_time DESC ";
        return $this->db->queryAllRows($sqlFormat, $hostId);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
