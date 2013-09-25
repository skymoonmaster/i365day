<?php /* Smarty version Smarty-3.1.3, created on 2013-09-25 11:27:54
         compiled from "/home/sid/project/php/cngtotools/trunk/i365day/application/views/email/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:1111505139524257ba3d83d2-79475482%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35e92538e109a29a0841920981e7c4a3dae2739b' => 
    array (
      0 => '/home/sid/project/php/cngtotools/trunk/i365day/application/views/email/index.phtml',
      1 => 1380079673,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1111505139524257ba3d83d2-79475482',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_524257ba3fa4a',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524257ba3fa4a')) {function content_524257ba3fa4a($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>绑定邮箱--365每天记</title>
    <link rel="stylesheet" href="../statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="../statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="container-email">
        <!--header开始-->
        <header>
        <div class="header-email clearfix">
            <h1 class="logo-email">
                <a href="index.html" title="logo"><img src="../statics/images/header-logo.png" alt="365Logo"></a>
                <span class="logo-slogan bind-email">每日一张照片，记录分享生活</span>
            </h1>
        </div>
        </header>
        <!--header结束-->
        <!--main开始-->
        <div class="main-email clearfix line">
            <h2>欢迎使用365.com!<span>还有一步，立刻开始365之旅！</span></h2>
            <!--用户信息开始-->
            <div class="user-content">
                <div class="user-pic"><a href="#" title=""><img src="../statics/images/user-info-img.gif" width="91" height="91"></a></div>
                <div class="user-info">
                    <div class="user-info-name"><a href="#" title="CSI">CSI</a></div>
                    <div class="user-info-position">
                    <a href="#" title="北京">北京</a>
                    </div>
                </div>
            </div>
            <!--用户信息结束-->
            <!--默认邮箱开始-->
            <div class="default-email">
                <form>
                    <label>默认邮箱：<input type="text" placeholder="默认邮箱用于找回您的账号，请放心填写。"/></label>
                    <a href="javascript:;" title="确认并进入365DAYS" class="default-email-submit"></a>
                </form>
            </div>
            <!--默认邮箱结束-->
        </div>
        <!--main结束-->
        <div class="footer-load"></div>
    </div>
    <script src="../statics/js/jquery-1.10.1.min.js"></script>
    <script src="../statics/js/temp-ajax-load.js"></script>
</body>
</html><?php }} ?>