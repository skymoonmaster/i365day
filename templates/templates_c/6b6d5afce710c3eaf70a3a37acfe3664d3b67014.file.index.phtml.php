<?php /* Smarty version Smarty-3.1.3, created on 2014-01-20 14:39:12
         compiled from "/home/sid/project/php/github/i365day/application/views/like/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:5888489952dcc490ee48d9-83498315%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6b6d5afce710c3eaf70a3a37acfe3664d3b67014' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/like/index.phtml',
      1 => 1388376952,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5888489952dcc490ee48d9-83498315',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user_diary_list' => 0,
    'diary' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52dcc49101f57',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52dcc49101f57')) {function content_52dcc49101f57($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
?><!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>我的喜欢--365每天记</title>
        <link rel="stylesheet" href="/statics/css/common.css">
        <!--[if lt IE 9]>
        <script src="/statics/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="header-load"></div>
        <div class="main">
            <div class="home-load"></div>
            <section class="like">
                <ul class="like-list clearfix">
                    <?php  $_smarty_tpl->tpl_vars['diary'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['diary']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['user_diary_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['diary']->key => $_smarty_tpl->tpl_vars['diary']->value){
$_smarty_tpl->tpl_vars['diary']->_loop = true;
?>
                    <li class="like-item">
                        <div class="all-hot-content clearfix">
                            <img src="<?php echo $_smarty_tpl->tpl_vars['diary']->value['thumbnail'];?>
"  width="251" height="251" class="all-hot-image">
                            <div class="all-hot-article">
                                <h3 class="all-hot-article-title"><a href="/diary/detail/diary_id/<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
" title="在家煮了砂锅粥"><?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
</a></h3>
                                <a class="article-author" href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['diary']->value['user_id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['diary']->value['nick_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['diary']->value['nick_name'];?>
</a><span class="article-time"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['diary']->value['date_ts'],"%Y-%m-%d");?>
</span>
                            </div>
                            <div class="all-hot-evaluate">
                                <div>
                                    <a href="#" class="evaluate-good clearword" title="喜欢">喜欢</a><em><?php echo $_smarty_tpl->tpl_vars['diary']->value['fav_num'];?>
</em>
                                </div>
                                <div>
                                    <a href="#" class="evaluate-weak clearword" title="comment">评论</a><em><?php echo $_smarty_tpl->tpl_vars['diary']->value['comment_num'];?>
</em>
                                </div>
                            </div>
                        </div>

                    </li>
                    <?php } ?>

                </ul>
            </section>
            <div class="paging-load"></div>
        </div>
        <div class="footer-load"></div>
        <script src="/statics/js/jquery-1.10.1.min.js"></script>
        <script src="/statics/js/temp-ajax-load.js"></script>
        <script src="/statics/js/common.js"></script>
    </body>
</html><?php }} ?>