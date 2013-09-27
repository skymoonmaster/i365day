<?php /* Smarty version Smarty-3.1.3, created on 2013-09-25 11:07:30
         compiled from "/home/sid/project/php/cngtotools/trunk/i365day/application/views/msg/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:118561378252425372603b61-36566099%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '381bc5d897ed0234066c55f36ae5951a4098efb1' => 
    array (
      0 => '/home/sid/project/php/cngtotools/trunk/i365day/application/views/msg/index.phtml',
      1 => 1380078407,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '118561378252425372603b61-36566099',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_5242537261cc7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5242537261cc7')) {function content_5242537261cc7($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>留言板--365每天记</title>
    <link rel="stylesheet" href="../statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="../statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header-load"></div>
    <div class="main">
        <div class="home-load"></div>
        <section class="home-note-wrap">
            <div class="home-nav-wrap clearfix">
                <ul class="home-tab-wrap clearfix">
                      <li class="home-tab-item"><a href="/home" title="我的365">我的365</a></li>
                    <li class="home-tab-item"><a href="/like" title="喜欢">喜欢</a></li>
                    <li class="home-tab-item current"><a href="/msg" title="留言板">留言板</a></li>
                </ul>
                <a class="write-now clearword" href="newnote.html" title="记录今天">记录今天</a>
            </div>
        </section>
        <section class="leave-message-wrap">
            <div class="leave-message">
                <textarea class="comment-text" name="" id="" placeholder="写留言吧"></textarea>
                <div class="comment-btn-wrap">
                    <button class="comment-btn" type="button" title="留言">留 言</button>
                </div>
                <div class="comment-load"></div>
                <div class="paging-load"></div>
            </div>
        </section>
    </div>
    <div class="footer-load"></div>
    <script src="../statics/js/jquery-1.10.1.min.js"></script>
    <script src="../statics/js/temp-ajax-load.js"></script>
    <script src="../statics/js/common.js"></script>
</body>
</html><?php }} ?>