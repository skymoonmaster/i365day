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

    public function getLeavingMsgByOwnerId($ownerId) {
        if (!$ownerId || intval($ownerId) == 0) {
            throw new Exception_BadInput("bad input owner id");
        }
        return $this->getSingleDataByConditions(array('owner_id' => $ownerId));
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
