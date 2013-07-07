<?php

Class Exception_Ajax extends Exception {
    
    public function getoutPutMsg(){
        $errorMsg = $this->getMessage();
        $errorData = json_decode($errorMsg, true);
        unset($errorData['log_msg']);
        return json_encode($errorData);
    }
    public function getLogMsg(){
        $errorMsg = $this->getMessage();
        $errorData = json_decode($errorMsg, true);
        return $errorData['log_msg'];
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
