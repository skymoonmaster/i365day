<?php /* Smarty version Smarty-3.1.3, created on 2013-12-25 13:19:12
         compiled from "/home/sid/project/php/github/i365day/application/views/home/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:81733004852ba6ad0944847-27772709%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '303d7ee5308ce5a734a4094f5c6ee82fbf46dae2' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/home/index.phtml',
      1 => 1387603356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '81733004852ba6ad0944847-27772709',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'diary_list' => 0,
    'diary' => 0,
    'first_date_ts' => 0,
    'is_record_today_show' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52ba6ad0e0541',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ba6ad0e0541')) {function content_52ba6ad0e0541($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
?><!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>我的首页--365每天记</title>
        <link rel="stylesheet" href="/statics/css/common.css">
        <!--[if lt IE 9]>
        <script src="/statics/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="header-load"></div>
        <div class="main">
            <div class="home-load"></div>
            <section class="home-note-wrap">
                <div class="home-note-list">
                    <h2 class="home-note-month"><em></em></h2>
                    <a href="#" class="home-date-btn clearword">选择年月</a>
                    <div class="home-date-wrap">
                        <div class="home-year-wrap">
                            <a class="home-year-prev" href="#" title="前一年">&lt;</a>
                            <span class="home-year-num"></span>
                            <a class="home-year-next" href="#" title="后一年">&gt;</a>
                        </div>
                        <ul class="home-month-wrap clearfix">
                        </ul>
                    </div>
                    <ul class="home-week-wrap">
                        <li class="home-week-item">SUN</li>
                        <li class="home-week-item">MON</li>
                        <li class="home-week-item">TUE</li>
                        <li class="home-week-item">WED</li>
                        <li class="home-week-item">THU</li>
                        <li class="home-week-item">FRI</li>
                        <li class="home-week-item">SAT</li>
                    </ul>
                    <div class="home-list">
                        <ul class="home-list-wrap clearfix">
                            <?php  $_smarty_tpl->tpl_vars['diary'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['diary']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['diary_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['diary']->key => $_smarty_tpl->tpl_vars['diary']->value){
$_smarty_tpl->tpl_vars['diary']->_loop = true;
?>
                            <?php if (isset($_smarty_tpl->tpl_vars['diary']->value['diary_id'])){?>
                            <li class="home-list-item">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['diary']->value['thumbnail'];?>
" alt="" class="home-note-img">
                                <div class="home-note-link-mask"></div>
                                <a href="/diary/detail/diary_id/<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
" class="home-note-link" title="查看日记">
                                    <span class="home-note-the">第<em class="home-note-days"><?php echo ($_smarty_tpl->tpl_vars['diary']->value['date_ts']-$_smarty_tpl->tpl_vars['first_date_ts']->value)/86400+1;?>
</em>日</span>
                                    <time class="home-note-date"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['diary']->value['date_ts'],"%m月%d日");?>
</time>
                                </a>
                            </li>
                            <?php }else{ ?>
                            <li class="home-list-item note-empty">
                                <p class="note-empty-text">放空的<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['diary']->value['date'],"%m月%d日");?>
</p>
                                <a class="fill-in-note" href="/diary/create/date/<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['diary']->value['date'],"%Y%m%d");?>
" title="补日记">+补日记</a>
                            </li>
                            <?php }?>
                            <?php } ?>
                            <?php if ($_smarty_tpl->tpl_vars['is_record_today_show']->value){?><li class="home-list-item note-add" title="记录今天"><a class="clearword" href="/diary/create" title="记录今天">记录今天</a></li><?php }?>
                        </ul>
                        <a href="#" class="home-list-prev clearword" title="上个月份">上个月份</a>
                        <a href="#" class="home-list-next clearword" title="下个月份">下个月份</a>
                    </div>     
                </div>
            </section>
        </div>
        <div class="footer-load"></div>
        <script src="/statics/js/jquery-1.10.1.min.js"></script>
        <script src="/statics/js/temp-ajax-load.js"></script>
        <script src="/statics/js/common.js"></script>
    </body>
</html><?php }} ?>