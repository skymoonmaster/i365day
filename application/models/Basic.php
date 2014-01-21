<?php

Class BasicModel {

    /**
     * @var DB_ProxyWrapper
     */
    protected $db;
    protected $table = '';
    protected $primaryKey = '';

    public static $statusDel = -1;
    public static $statusNormal = 0;
    protected function __construct() {
        $this->db = DB_ProxyWrapper::getInstance(I365DAY);
    }

    public function create($data) {
        if (!is_array($data) || count($data) == 0) {
            return false;
        }
        $ret = $this->db->insert($data, $this->table);
        if (!$ret) {
            return false;
        }
        return $this->db->getLastInsertID();
    }
    public function update($data) {
        if (!$data || !is_array($data) || !isset($data[$this->primaryKey]) || !$data[$this->primaryKey]) {
            throw new Exception_BadInput("bad input data for $this->table");
        }
        $sqlUpdateDiary = $this->db->buildUpdateSqlStr($data, $this->table);
        $sqlUpdateDiary .= " WHERE $this->primaryKey = {$data[$this->primaryKey]}";
        return $this->db->update($sqlUpdateDiary);
    }
    public function createWithTimestamp($data) {
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

    public function getSingleDataByConditions($columnKeyToValues) {
        $sqlFormat = "SELECT * FROM $this->table WHERE 1=1 ";
        foreach ($columnKeyToValues as $key => $value) {
            if (!$key) {
                continue;
            }
            $sqlFormat .= " AND $key = '" . $this->db->realEscapeString($value) . "'";
        }
        return $this->db->queryFirstRow($sqlFormat);
    }
    public function getAmountByConditions($columnKeyToValues) {
        $sqlFormat = "SELECT count(*) as cnt FROM $this->table WHERE 1=1 ";
        foreach ($columnKeyToValues as $key => $value) {
            if (!$key) {
                continue;
            }
            $sqlFormat .= " AND $key = '" . $this->db->realEscapeString($value) . "'";
        }
        $ret = $this->db->queryFirstRow($sqlFormat);
        return intval($ret['cnt']) > 0 ? intval($ret['cnt']) : 0;
    }
    
    public function getDataListByConditions($columnKeyToValues) {
        $sqlFormat = "SELECT * FROM $this->table WHERE 1=1 ";
        foreach ($columnKeyToValues as $key => $value) {
            if (!$key) {
                continue;
            }
            $sqlFormat .= " AND $key = '" . $this->db->realEscapeString($value) . "'";
        }
        return $this->db->queryAllRows($sqlFormat);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
