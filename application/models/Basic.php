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

    public function create($data) {
        if (!is_array($data) || count($data) == 0) {
            return false;
        }
        
        return $this->db->insert($data, $this->table);
    }
    
    public function createWithTimestamp($data) {
        if (!is_array($data) || count($data) == 0) {
            return false;
        }
        if(!isset($data['create_time'])){
            $data['create_time'] = time();
        }
        $data['update_time'] = time();
        return $this->db->insert($data, $this->table);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
