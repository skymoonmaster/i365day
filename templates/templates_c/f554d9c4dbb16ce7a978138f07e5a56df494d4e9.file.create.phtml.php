<?php /* Smarty version Smarty-3.1.3, created on 2013-12-25 13:19:19
         compiled from "/home/sid/project/php/github/i365day/application/views/diary/create.phtml" */ ?>
<?php /*%%SmartyHeaderCode:86661910952ba6ad7e82076-55215423%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f554d9c4dbb16ce7a978138f07e5a56df494d4e9' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/diary/create.phtml',
      1 => 1387603354,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86661910952ba6ad7e82076-55215423',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'date' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52ba6ad7f37b0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ba6ad7f37b0')) {function content_52ba6ad7f37b0($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>发布新日记--365每天记</title>
    <link rel="stylesheet" href="/statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="/statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header-load"></div>
    <div class="main">
        <form action="/diary/docreate" method="post" enctype="multipart/form-data">
            <h1 class="main-title-icon">发布新日记</h1>
            <div class="note-content line">
                <div class="note-form">
                    <div class="note-img-wrap clearfix">
                        <div class="note-date-wrap clearfix">
                            <div class="note-date-label">
                                <strong class="note-date-text">DAY</strong>
                                <em class="note-date-days">212</em>
                            </div>
                            <time class="note-date">2013.7.6</time>
                        </div>
                        <div class="note-img-window">
                            <div class="note-img-tips">
                                <i class="note-img-icon"></i>
                                <p>目前仅支持jpg、png格式</p>
                            </div>
                            <img class="note-img"  src="" alt="">
                            <input type="file" class="note-img-upload" name="pic" title="点击选择文件">
                        </div>
                        <input type="text" class="note-img-description" name="pic_desc" placeholder="图片说明...(可选)">
                    </div>
                    <div class="note-text-wrap">
                        <input type="text" class="note-text-title" name="title" placeholder="填写标题...(可选)">
                        <textarea class="note-text" name="content"></textarea>
                        <!-- <input type="text" class="note-text-label" placeholder="#标签"> -->
                        <ul class="extendinputwrap"><li class="otheritem"><input id="city" name="tags[]" type="text" placeholder="#标签" maxlength="20" /></li></ul>
                    </div>
                </div>
                <div class="note-btn-wrap">
                    <button class="note-send clearword" type="submit" title="写好了,发布">写好了,发布</button>
                    <label><input type="checkbox" class="note-secret" name="private" />仅自己可见</label>
                    <div class="note-sync-wrap">
                        <span class="note-sync-text">同步到:</span>
                        <a href="#" title="sina" class="login-sina">新浪登录</a>
                        <a href="#" title="dou" class="login-dou">豆瓣登录</a>
                    </div>
                </div>
            </div>
            <input type ="hidden" name ='date' value ="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
"/>
        </form>
    </div>
    <div class="footer-load"></div>
    <script src="/statics/js/jquery-1.10.1.min.js"></script>
    <script src="/statics/js/temp-ajax-load.js"></script>
    <script src="/statics/js/common.js"></script>
</body>
</html><?php }} ?>