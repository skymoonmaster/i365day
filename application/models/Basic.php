<?php

Class BasicModel {

    /**
     * @var DB_ProxyWrapper
     */
    protected $db;
    protected $table = '';

    protected function __construct() {
        $this->db = DB_ProxyWrapper::getInstance(I365DAY);
    }

    protected function create($data) {
        if (!is_array($data) || count($data) == 0) {
            return false;
        }
        $ret = $this->db->insert($data, $this->table);
        if (!$ret) {
            return false;
        }
        return $this->db->getLastInsertID();
    }

    protected function createWithTimestamp($data) {
        if (!is_array($data) || count($data) == 0) {
            return false;
        }
        if (!isset($data['create_time'])) {
            $data['create_time'] = time();
        }
        $data['update_time'] = time();
        $ret = $this->db->insert($data, $this->table);
        if (!$ret) {
            return false;
        }
        return $this->db->getLastInsertID();
    }

    protected function getSingleDiaryByConditions($columnKeyToValues) {
        $sqlFormat = "SELECT * FROM $this->table WHERE 1=1 ";
        foreach ($columnKeyToValues as $key => $value) {
            if (!$key) {
                continue;
            }
            $sqlFormat .= " AND $key = '" . $this->db->realEscapeString($value) . "'";
        }
        return $this->db->queryFirstRow($sqlFormat);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
