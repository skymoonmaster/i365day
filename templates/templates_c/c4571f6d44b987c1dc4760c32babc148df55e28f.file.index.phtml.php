<?php /* Smarty version Smarty-3.1.3, created on 2014-01-27 11:08:24
         compiled from "/home/sid/project/php/github/i365day/application/views/friend/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:92002798852e5cda89b52b2-22325626%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c4571f6d44b987c1dc4760c32babc148df55e28f' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/friend/index.phtml',
      1 => 1390273714,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92002798852e5cda89b52b2-22325626',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'feeds' => 0,
    'feed' => 0,
    'feed_content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52e5cda8b4a15',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52e5cda8b4a15')) {function content_52e5cda8b4a15($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>小伙伴的全部新鲜事--365每天记</title>
    <link rel="stylesheet" href="/statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="/statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header-load"></div>
    <div id="friend-content" class="clearfix">
        <div class="white-bg"></div>
        <div id="main" class="friend">
            <h1>全部动态</h1>
            <div class="main-content line">

                <?php  $_smarty_tpl->tpl_vars['feed'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['feed']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['feeds']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['feed']->key => $_smarty_tpl->tpl_vars['feed']->value){
$_smarty_tpl->tpl_vars['feed']->_loop = true;
?>
                    <?php $_smarty_tpl->tpl_vars["feed_content"] = new Smarty_variable(json_decode($_smarty_tpl->tpl_vars['feed']->value['content'],true), null, 0);?>
                    <?php if ($_smarty_tpl->tpl_vars['feed']->value['type']==3){?>
                    <div class="friend-state">
                        <a href="#" title="" class="friend-pic"><img src="/statics/images/friend-pic.gif"></a>
                        <div class="make-friend">
                            <a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_id'];?>
" class="friend-name" title="<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['feed']->value['user_name'];?>
</a>关注了<a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['feed_content']->value['followId'];?>
"" class="friend-name" title="<?php echo $_smarty_tpl->tpl_vars['feed_content']->value['followNickName'];?>
"><?php echo $_smarty_tpl->tpl_vars['feed_content']->value['followNickName'];?>
</a>
                        </div>
                    </div>
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['feed']->value['type']==1){?>
                    <div class="friend-state">
                        <a href="#" title="" class="friend-pic"><img src="/statics/images/friend-pic.gif"></a>
                        <div class="record">
                            <a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_id'];?>
" class="friend-name" title="<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['feed']->value['user_name'];?>
</a>记录了<?php echo $_smarty_tpl->tpl_vars['feed_content']->value['title'];?>

                            <div class="record-content clearfix">
                                <div class="pic">
                                    <a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_name'];?>
"><img src="/statics/images/friend.gif" width="100" height="100"/></a>
                                </div>
                                <a href="/diary/detail/diary_id/<?php echo $_smarty_tpl->tpl_vars['feed_content']->value['diary_id'];?>
" class="info">
                                    <h3><?php echo $_smarty_tpl->tpl_vars['feed_content']->value['title'];?>
</h3>
                                    <p><?php echo $_smarty_tpl->tpl_vars['feed_content']->value['content'];?>
</p>
                                </a>
                            </div>
                            <div class="publish-date"><?php echo $_smarty_tpl->tpl_vars['feed']->value['formatted_time'];?>
</div>
                        </div>
                    </div>
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['feed']->value['type']==2){?>
                    <div class="friend-state">
                        <a href="#" title="" class="friend-pic"><img src="/statics/images/friend-pic.gif"></a>
                        <div class="like-friend">
                            <a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_id'];?>
" class="friend-name" title="<?php echo $_smarty_tpl->tpl_vars['feed']->value['user_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['feed']->value['user_name'];?>
</a>喜欢了<a href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['feed_content']->value['authorId'];?>
" class="friend-name" title="<?php echo $_smarty_tpl->tpl_vars['feed_content']->value['authorName'];?>
"><?php echo $_smarty_tpl->tpl_vars['feed_content']->value['authorName'];?>
</a>的日记
                            <div class="like-content clearfix">
                                <div class="pic">
                                    <a href="#" title=""><img src="/statics/images/like.gif" width="70" height="70"/></a>
                                </div>
                                <a href="#" class="info">
                                    <h3><?php echo $_smarty_tpl->tpl_vars['feed_content']->value['title'];?>
</h3>
                                    <p><?php echo $_smarty_tpl->tpl_vars['feed_content']->value['content'];?>
</p>
                                </a>
                            </div>
                            <div class="publish-date"><?php echo $_smarty_tpl->tpl_vars['feed']->value['formatted_time'];?>
</div>
                        </div>
                    </div>
                    <?php }?>
                <?php } ?>

<!--                <div class="paging-load"></div>-->
            </div>
        </div>
        <div id="sidebar">
            <h2>我的关注 <a href="interest.html" class="myfocus-num">78</a></h2>
            <div class="sidebar-content">
                <div class="myfocus clearfix">
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">ezuojuzhiwen</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">ezuojuzhiwen</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">who</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">ezuojuzhiwen</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">EXO</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">who</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">一吻定情</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">入江裕树</span>
                    </a>
                    <a href="#" title="">
                        <img src="/statics/images/friend-pic.gif" width="50" height="50">
                        <span class="myfocus-name">who</span>
                    </a>
                </div>
            </div>
            <aside class="all-hot">
                <h2 class="all-hot-title">全站最热</h2>
                <div class="like-load"></div>
            </aside>
        </div>
    </div>
    <div class="footer-load"></div>
    <script src="/statics/js/jquery-1.10.1.min.js"></script>
    <script src="/statics/js/temp-ajax-load.js"></script>
    <script src="/statics/js/common.js"></script>
</body>
</html><?php }} ?>