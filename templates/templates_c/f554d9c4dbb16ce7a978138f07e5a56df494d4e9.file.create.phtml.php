<?php /* Smarty version Smarty-3.1.3, created on 2014-01-27 13:45:07
         compiled from "/home/sid/project/php/github/i365day/application/views/diary/create.phtml" */ ?>
<?php /*%%SmartyHeaderCode:163246163752d7915de34959-33585963%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f554d9c4dbb16ce7a978138f07e5a56df494d4e9' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/diary/create.phtml',
      1 => 1390801506,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '163246163752d7915de34959-33585963',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52d7915de6922',
  'variables' => 
  array (
    'duration' => 0,
    'date_ts' => 0,
    'is_admin' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d7915de6922')) {function content_52d7915de6922($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
?><!doctype html>
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
                                <em class="note-date-days"><?php echo $_smarty_tpl->tpl_vars['duration']->value;?>
</em>
                            </div>
                            <time class="note-date"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date_ts']->value,"%Y.%m.%d");?>
</time>
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
                    <?php if ($_smarty_tpl->tpl_vars['is_admin']->value){?>
                        <select name="type" class="note-text-title" style="width:220px; margin-left: 20px;" >
                            <option value="0">选择日记类型</option>
                            <option value="1">365周报</option>
                            <option value="2">365线下聚会</option>
                            <option value="3">365黑板报</option>
                            <option value="4">365明星同学</option>
                            <option value="5">其他</option>
                        </select>
                    <?php }?>
                    <div class="note-sync-wrap">
                        <span class="note-sync-text">同步到:</span>
                        <a href="#" title="sina" class="login-sina">新浪登录</a>
                        <a href="#" title="dou" class="login-dou">豆瓣登录</a>
                    </div>
                </div>
            </div>
            <input type ="hidden" name ='date' value ="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date_ts']->value,"%Y%m%d");?>
"/>
        </form>
    </div>
    <div class="common-dialog j-photo-alert">
        <div class="common-dialog-title">提示</div>
        <p class="common-dialog-text">亲，有真相，才有亲和力哦！</p>
        <a href="#" class="common-dialog-ok j-photo-ok" title="上传图片">上传图片</a>
        <a href="#" class="common-dialog-close clearword j-photo-close" title="关闭">关闭</a>
    </div>
    <div class="footer-load"></div>
    <script src="/statics/js/jquery-1.10.1.min.js"></script>
    <script src="/statics/js/temp-ajax-load.js"></script>
    <script src="/statics/js/common.js"></script>
</body>
</html><?php }} ?>