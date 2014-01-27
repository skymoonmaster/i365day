<?php /* Smarty version Smarty-3.1.3, created on 2014-01-14 16:11:40
         compiled from "/home/sid/project/php/github/i365day/application/views/lib/header.phtml" */ ?>
<?php /*%%SmartyHeaderCode:88378838452d4f13ce4d0a8-77800806%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '90cdb7593b8e14889fe262f6f9bb0a422cff7757' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/lib/header.phtml',
      1 => 1389683092,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '88378838452d4f13ce4d0a8-77800806',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'current_page' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52d4f13d0a739',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d4f13d0a739')) {function content_52d4f13d0a739($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>新鲜事--365每天记</title>
    <link rel="stylesheet" href="/statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="/statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <header class="header">
        <div class="header-wrap clearfix">
            <div class="nav clearfix">
                <strong class="logo"><a class="clearword" href="index.html" title="365每天记">365每天记</a></strong>
                <nav>
                    <ul class="nav-wrap clearfix">
                        <li class="nav-item <?php if ($_smarty_tpl->tpl_vars['current_page']->value=='home'||$_smarty_tpl->tpl_vars['current_page']->value=='like'||$_smarty_tpl->tpl_vars['current_page']->value=='msg'){?>current<?php }?>"><a href="/home" title="我的首页">我的首页</a></li>
                        <li class="nav-item <?php if ($_smarty_tpl->tpl_vars['current_page']->value=='friend'){?>current<?php }?>"><a href="/friend" title="小伙伴">小伙伴</a></li>
                        <li class="nav-item <?php if ($_smarty_tpl->tpl_vars['current_page']->value=='news'){?>current<?php }?>"><a href="/news" title="黑板报">黑板报</a></li>
                    </ul>
                </nav>
            </div>
            <div class="login-info clearfix">
                <a class="message-num" href="#" title="" style="display: none;">0</a>
                <div class="info-username clearfix">
                    <a class="info-userlink" href="#" title="meixiaofang"><?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
</a>
                    <b></b>
                    <ul class="user-menu">
                        <li><a class="menu-item menu-message" href="#" title="查看消息">消息</a></li>
                        <li><a class="menu-item menu-setting" href="setting" title="修改设置">设置</a></li>
                        <li><a class="menu-item menu-invite" href="invite" title="邀请朋友">邀请</a></li>
                        <li><a class="menu-item menu-logout" href="#" title="明天见!">退出</a></li>
                    </ul>
                </div>
                <div class="message j-message" id="message-board">
                    <strong class="message-title">消息中心</strong>
                    <ul class="message-wrap" id="message-list">
                    </ul>
                    <!--a href="#" class="message-close clearword j-message-close" id="message-close" title="关闭消息中心">关闭消息中心</a-->
                </div>
            </div>
        </div>
    </header>
    <script src="/statics/js/jquery-1.10.1.min.js"></script>
    <script src="/statics/js/common.js"></script>
</body>
</html><?php }} ?>