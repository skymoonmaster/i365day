<?php /* Smarty version Smarty-3.1.3, created on 2013-12-25 13:22:48
         compiled from "/home/sid/project/php/github/i365day/application/views/msg/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:65238356052ba6ba81f6c43-69229912%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bba7f26aa24b5ab8fdfbb0c64e8083ab3f6319db' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/msg/index.phtml',
      1 => 1387603359,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '65238356052ba6ba81f6c43-69229912',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52ba6ba82a383',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ba6ba82a383')) {function content_52ba6ba82a383($_smarty_tpl) {?><!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>留言板--365每天记</title>
        <link rel="stylesheet" href="/statics/css/common.css">
        <!--[if lt IE 9]>
        <script src="/statics/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="header-load"></div>
        <div class="main">
            <div class="home-load"></div>
            <section class="leave-message-wrap">
                <form action="/leavingMsg/docreate" method="post">
                    <input type="hidden" name="follow_id" id="follow_id"/>
                    <input type="hidden" name="host_id" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['user_id'];?>
">
                    <div class="leave-message">
                        <textarea class="comment-text" name="content" id="" placeholder="写留言吧"></textarea>
                        <div class="comment-btn-wrap">
                            <button class="comment-btn" type="submit" title="留言">留 言</button>
                        </div>
                        <div class="leavingmsg-load"></div>
                        <div class="paging-load"></div>
                    </div>
                </form>
            </section>
        </div>
        <div class="footer-load"></div>
        <script src="/statics/js/jquery-1.10.1.min.js"></script>
        <script src="/statics/js/temp-ajax-load.js"></script>
        <script src="/statics/js/common.js"></script>
    </body>
</html><?php }} ?>