<?php

/* * *************************************************************************
 * 
 * Copyright (c) 2013 i365.com, Inc. All Rights Reserved
 * 
 * ************************************************************************ */

/**
 * 
 * @package	controllers
 * @author	yp
 * @version	$Revision: 1.1 $
 */
class IndexController extends BasicController {
    public function indexAction() {
        echo 1111;
        var_dump($_GET['inv']);
        var_dump($this->getOptionalParam('inv', null));

        //TODO 是否携带Cookie
        //TODO 是否携带邀请码
        $hasCookie = false;
        if ($hasCookie) {
            $this->redirect("/home");
        }

        $_SESSION['invite_code'] = $this->getOptionalParam('invite_code', null);
    }


}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>