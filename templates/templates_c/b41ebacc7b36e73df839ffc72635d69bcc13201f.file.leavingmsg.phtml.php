<?php /* Smarty version Smarty-3.1.3, created on 2014-01-22 13:51:30
         compiled from "/home/sid/project/php/github/i365day/application/views/lib/leavingmsg.phtml" */ ?>
<?php /*%%SmartyHeaderCode:37715554852dcc4951f4970-95337144%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b41ebacc7b36e73df839ffc72635d69bcc13201f' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/lib/leavingmsg.phtml',
      1 => 1390369883,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '37715554852dcc4951f4970-95337144',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52dcc49527233',
  'variables' => 
  array (
    'leaving_msg_list' => 0,
    'msg' => 0,
    'leaving_msg_associate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52dcc49527233')) {function content_52dcc49527233($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="/statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <ul class="leavingmsg-detail">
        <?php  $_smarty_tpl->tpl_vars['msg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['msg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['leaving_msg_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['msg']->key => $_smarty_tpl->tpl_vars['msg']->value){
$_smarty_tpl->tpl_vars['msg']->_loop = true;
?>
            <li class="comment-item">
                <img class="comment-head" src="/statics/images/message_img_smallhead.jpg" alt="头像">
                <div class="comment-main">
                    <?php if ($_smarty_tpl->tpl_vars['msg']->value['follow_id']!=0){?>
                        <div class="reply-quote">
                            <span><?php echo $_smarty_tpl->tpl_vars['leaving_msg_associate']->value[$_smarty_tpl->tpl_vars['msg']->value['follow_id']]['content'];?>
</span><a href="#" class="reply-to-author" title="<?php echo $_smarty_tpl->tpl_vars['msg']->value['vistor_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['msg']->value['vistor_name'];?>
</a>
                        </div>
                    <?php }?>
                    <p class="comment-content"><a class="article-author" href="#" title="<?php echo $_smarty_tpl->tpl_vars['msg']->value['vistor_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['msg']->value['vistor_name'];?>
:</a><?php echo $_smarty_tpl->tpl_vars['msg']->value['content'];?>
</p>
                    <p class="comment-other">
                        <time class="article-time"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['msg']->value['create_time'],"%Y年%m月%d日 %H:%M");?>
</time><a href="" class="reply" title="回复" alt ="<?php echo $_smarty_tpl->tpl_vars['msg']->value['leaving_msg_id'];?>
">回复</a><a href="#" class="delete" title="删除" alt ="<?php echo $_smarty_tpl->tpl_vars['msg']->value['leaving_msg_id'];?>
">删除</a>
                    </p>
                </div>
            </li>
        <?php } ?>
    </ul>
</body>
</html><?php }} ?>