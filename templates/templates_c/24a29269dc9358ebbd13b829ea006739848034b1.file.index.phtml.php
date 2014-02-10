<?php /* Smarty version Smarty-3.1.3, created on 2014-01-27 11:07:39
         compiled from "/home/sid/project/php/github/i365day/application/views/follow/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:133265713352e5cd7b4ed6c0-95944662%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24a29269dc9358ebbd13b829ea006739848034b1' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/follow/index.phtml',
      1 => 1390792040,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '133265713352e5cd7b4ed6c0-95944662',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_self' => 0,
    'userInfo' => 0,
    'follows' => 0,
    'follow' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52e5cd7b5e3c4',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52e5cd7b5e3c4')) {function content_52e5cd7b5e3c4($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的关注--365每天记</title>
    <link rel="stylesheet" href="/statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="/statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header-load"></div>
    <div class="main">
        <div class="home-load"></div>

        <section class="fans">
            <div class="fans-title-wrap clearfix line">
                <div class="fans-title">
                    <a href="#" class="inline-block fans-myinterest current"><?php if ($_smarty_tpl->tpl_vars['is_self']->value){?>我<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['nick_name'];?>
<?php }?>的关注<em><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['follows'];?>
</em></a>
                    <a href="/fans<?php if (!$_smarty_tpl->tpl_vars['is_self']->value){?>/index/p/<?php echo $_smarty_tpl->tpl_vars['userInfo']->value['user_id'];?>
<?php }?>" class="inline-block fans-myfans"><?php if ($_smarty_tpl->tpl_vars['is_self']->value){?>我<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['nick_name'];?>
<?php }?>的粉丝<em><?php echo $_smarty_tpl->tpl_vars['userInfo']->value['fans'];?>
</em></a>
                </div>
<!--                <div class="news-search">-->
<!--                    <form>-->
<!--                        <input type="text" placeholder="在列表中搜索朋友">-->
<!--                        <button class="news-search-submit clearword" type="submit" title="搜索">搜索</button>-->
<!--                    </form>-->
<!--                </div>-->
            </div>
            <ul class="fans-list clearfix">
                <?php  $_smarty_tpl->tpl_vars['follow'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['follow']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['follows']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['follow']->key => $_smarty_tpl->tpl_vars['follow']->value){
$_smarty_tpl->tpl_vars['follow']->_loop = true;
?>
                <li class="fans-item">
                    <a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" class="fans-head" title="<?php echo $_smarty_tpl->tpl_vars['follow']->value['nick_name'];?>
"><img src="/statics/images/fans_img_head.jpg" alt=""></a>
                    <a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" class="fans-name" title="<?php echo $_smarty_tpl->tpl_vars['follow']->value['nick_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['follow']->value['nick_name'];?>
</a>

                    <a href="#" class="quxiao-guanzhu cancel-attention" id="cancel-attention-<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" title="取消关注" alt="<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" <?php if ((!$_smarty_tpl->tpl_vars['is_self']->value)&&(!$_smarty_tpl->tpl_vars['follow']->value['is_follow'])){?>style="display: none"<?php }?>>取消关注</a>
                    <a href="#" class="guanzhu add-attention" id="add-attention-<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" title="关注" alt="<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" user-name="<?php echo $_smarty_tpl->tpl_vars['follow']->value['nick_name'];?>
" <?php if ($_smarty_tpl->tpl_vars['is_self']->value||$_smarty_tpl->tpl_vars['follow']->value['is_follow']){?>style="display: none"<?php }?>>关注</a>

                    <div class="fans-detail">
                        <a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" class="fans-day" title="查看他的日记"><?php echo $_smarty_tpl->tpl_vars['follow']->value['diary_days'];?>
个日子</a>
                        <a href="/fans/index/p/<?php echo $_smarty_tpl->tpl_vars['follow']->value['user_id'];?>
" class="fans-fans" title="查看他的粉丝"><?php echo $_smarty_tpl->tpl_vars['follow']->value['fans'];?>
个粉丝</a>
                    </div>
                </li>
                <?php } ?>
            </ul>
            <div class="paging-load"></div>
        </section>
    </div>
    <div class="footer-load"></div>
    <script src="/statics/js/jquery-1.10.1.min.js"></script>
    <script src="/statics/js/temp-ajax-load.js"></script>
    <script src="/statics/js/common.js"></script>
</body>
</html><?php }} ?>