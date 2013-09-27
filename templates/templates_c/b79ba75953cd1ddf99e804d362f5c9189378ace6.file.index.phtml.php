<?php /* Smarty version Smarty-3.1.3, created on 2013-09-25 11:29:45
         compiled from "/home/sid/project/php/cngtotools/trunk/i365day/application/views/invite/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:1051718836524258a9e3a453-62639564%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b79ba75953cd1ddf99e804d362f5c9189378ace6' => 
    array (
      0 => '/home/sid/project/php/cngtotools/trunk/i365day/application/views/invite/index.phtml',
      1 => 1380079056,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1051718836524258a9e3a453-62639564',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_524258a9e534d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524258a9e534d')) {function content_524258a9e534d($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>邀请好友--365每天记</title>
    <link rel="stylesheet" href="../statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="../statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header-load"></div>
    <div class="main">
        <h1 class="main-title-icon invite-title">邀请好友</h1>
        <div class="invite-main line">
            <p>目前365day正在内测中，您可以发送以下链接给好友，邀请好友加入。
好友加入后，邀请链接会失效，好友会自动关注你。</p>
            <div class="invite-message">
                <h2>您有<span class="invite-num">3</span>个邀请名额</h2>
                <div class="invite-info">
                        <input type="text" name="" value="http://invite.365.com/?haha12345*user=12345" class="copy-info"/>
                        <a href="javascript:;" title="复制" class="copy-btn">复制</a>
                </div>
                <div class="invite-info">
                        <input type="text" name="" value="http://invite.365.com/?haha12345*user=555555" class="copy-info"/>
                        <a href="javascript:;" title="复制" class="copy-btn">复制</a>
                </div>
                <div class="invite-info">
                        <input type="text" name="" value="http://invite.365.com/?haha12345*user=555555" class="copy-info"/>
                        <a href="javascript:;" title="复制" class="copy-btn">复制</a>
                </div>
            </div>                
        </div>
    </div>
    <div class="footer-load"></div>
    <script src="../statics/js/jquery-1.10.1.min.js"></script>
    <script src="../statics/js/temp-ajax-load.js"></script>
    <script src="../statics/js/common.js"></script>
</body>
</html><?php }} ?>