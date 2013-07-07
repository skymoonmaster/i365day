<?php

Class BasicModel {
    /**
     * @var DB_ProxyWrapper
     */
    protected $db;
    protected function __construct()
    {
        $this->db = DB_ProxyWrapper::getInstance(COST_PLATFORM);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
