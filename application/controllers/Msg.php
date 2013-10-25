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
class MsgController extends BasicController {

    public function indexAction() {
       $this->getView()->assign('user', $this->userInfo);
    }
}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>