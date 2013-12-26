<?php /* Smarty version Smarty-3.1.3, created on 2013-12-25 13:19:16
         compiled from "/home/sid/project/php/github/i365day/application/views/lib/home.phtml" */ ?>
<?php /*%%SmartyHeaderCode:27674378952ba6ad49cab76-41956972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0068eea5749f894d19694491288aded0d279944e' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/lib/home.phtml',
      1 => 1387603358,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27674378952ba6ad49cab76-41956972',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'is_self' => 0,
    'duration' => 0,
    'current_page' => 0,
    'current_user_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52ba6ad507a59',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ba6ad507a59')) {function content_52ba6ad507a59($_smarty_tpl) {?><!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" href="/statics/images/common.css">
        <!--[if lt IE 9]>
        <script src="/statics/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <section class="home-info-wrap clearfix">
            <div class="user-info-wrap clearfix">
                <img src="/statics/images/home_img_head.jpg" alt="头像" width="90" height="90" class="head-sculpture">
                <div class="user-info-innerwrap">
                    <h1 class="username"><?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
</h1>
                    <p class='usercity-wrap'><span class="usercity"><?php echo $_smarty_tpl->tpl_vars['user']->value['city'];?>
</span><span class="device">使用<?php echo $_smarty_tpl->tpl_vars['user']->value['camera_brand'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['camera_model'];?>
</span></p>
                    <?php if (!$_smarty_tpl->tpl_vars['is_self']->value){?>
                    <p class="user-info-guanzhu">
                        <a href="#" class="guanzhu" title="关注他">关注他</a>
                        <a href="#" class="quxiao-guanzhu" title="取消关注">取消关注</a>
                    </p>
                    <?php }?>
                    <div class="user-info-links">
                        <a class="douban clearword" href="#" title="豆瓣">豆瓣</a>
                        <a class="sina clearword" href="#" title="新浪微博">新浪微博</a></div>
                    <a class="user-info-setting clearword" href="#" title="设置">设置</a>
                </div>
            </div>
            <div class="day-wrap">
                <em class="day-num"><?php echo $_smarty_tpl->tpl_vars['duration']->value;?>
</em><span class="day-text">个日子</span>
            </div>
            <div class="user-description-wrap">
                <p><?php echo $_smarty_tpl->tpl_vars['user']->value['intro'];?>
</p>
                <a href="#" class="modify-description clearword" title="修改">修改</a>
            </div>
            <div class="user-fans-wrap">
                <p class="user-interest"><a href="interest.html" title="查看关注"><em class="user-interest-num"><?php echo $_smarty_tpl->tpl_vars['user']->value['follows'];?>
</em>个关注</a></p>
                <p class="user-fans"><a href="fans.html" title="查看粉丝"><em class="user-fans-num"><?php echo $_smarty_tpl->tpl_vars['user']->value['fans'];?>
</em>个粉丝</a></p>
            </div>
        </section>
        <section class="home-info-wrap clearfix">
            <div class="home-nav-wrap clearfix">
                <ul class="home-tab-wrap clearfix">
                    <li class="home-tab-item <?php if ($_smarty_tpl->tpl_vars['current_page']->value=='home'){?>current<?php }?>"><a href="/home<?php if (!$_smarty_tpl->tpl_vars['is_self']->value){?>/index/p/<?php echo $_smarty_tpl->tpl_vars['current_user_id']->value;?>
<?php }?>" title="我的365"><?php if ($_smarty_tpl->tpl_vars['is_self']->value){?>我<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
<?php }?>的365</a></li>
                    <li class="home-tab-item <?php if ($_smarty_tpl->tpl_vars['current_page']->value=='like'){?>current<?php }?>"><a href="/like<?php if (!$_smarty_tpl->tpl_vars['is_self']->value){?>/index/p/<?php echo $_smarty_tpl->tpl_vars['current_user_id']->value;?>
<?php }?>" title="喜欢">喜欢</a></li>
                    <li class="home-tab-item <?php if ($_smarty_tpl->tpl_vars['current_page']->value=='msg'){?>current<?php }?>"><a href="/msg<?php if (!$_smarty_tpl->tpl_vars['is_self']->value){?>/index/p/<?php echo $_smarty_tpl->tpl_vars['current_user_id']->value;?>
<?php }?>" title="留言板">留言板</a></li>
                </ul>
                <a class="write-now clearword" href="newnote.html" title="记录今天">记录今天</a>
            </div>
        </section>
        <script src="/statics/js/jquery-1.10.1.min.js"></script>
        <script src="/statics/js/common.js"></script>
        <script src="/statics/js/news.js"></script>
    </body>
</html><?php }} ?>