<?php /* Smarty version Smarty-3.1.3, created on 2014-01-27 14:05:39
         compiled from "/home/sid/project/php/github/i365day/application/views/diary/edit.phtml" */ ?>
<?php /*%%SmartyHeaderCode:95991376652d7960228a382-32251797%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd2de7ef3efb5d87d129c0b881e8ccc7da60fa5d4' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/diary/edit.phtml',
      1 => 1390801828,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '95991376652d7960228a382-32251797',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52d796023bfff',
  'variables' => 
  array (
    'duration' => 0,
    'date_ts' => 0,
    'diary' => 0,
    'tag' => 0,
    'is_admin' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d796023bfff')) {function content_52d796023bfff($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
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
        <form action="/diary/doedit" method="post" enctype="multipart/form-data">
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
                            <img class="note-img"  src="<?php echo $_smarty_tpl->tpl_vars['diary']->value['pic'];?>
" alt="" style="height: 288px; display: inline; width: 288px;">
                            <input type="file" class="note-img-upload" name="pic" title="点击选择文件">
                        </div>
                        <input type="text" class="note-img-description" name="pic_desc" placeholder="图片说明...(可选)" value="<?php echo $_smarty_tpl->tpl_vars['diary']->value['pic_desc'];?>
">
                    </div>
                    <div class="note-text-wrap">
                        <input type="text" class="note-text-title" name="title" placeholder="填写标题...(可选)" value="<?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
">
                        <textarea class="note-text" name="content"><?php echo $_smarty_tpl->tpl_vars['diary']->value['content'];?>
</textarea>
                        <ul class="extendinputwrap">
                            <?php  $_smarty_tpl->tpl_vars['tag'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tag']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['diary']->value['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->key => $_smarty_tpl->tpl_vars['tag']->value){
$_smarty_tpl->tpl_vars['tag']->_loop = true;
?>
                               <li class="extenditem">
                                <label><?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</label>
                                <a class="extendclose" href="#" title="移除此项"></a>
                                <input name="tags[]" value="<?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
" style="display:none;">
                                </li>
                            <?php } ?>
                            <li class="otheritem">
                                <input id="city" name="tags[]" type="text" placeholder="#标签" maxlength="20" />
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="note-btn-wrap">
                    <button class="note-send clearword" type="submit" title="写好了,发布">写好了,发布</button>
                    <label><input type="checkbox" class="note-secret" name="private" <?php if ($_smarty_tpl->tpl_vars['diary']->value['visibility']){?>checked<?php }?>/>仅自己可见</label>
                    <?php if ($_smarty_tpl->tpl_vars['is_admin']->value){?>
                        <select id="diary_type" name="type" class="note-text-title" style="width:220px; margin-left: 20px;" >
                            <option value="0" <?php if ($_smarty_tpl->tpl_vars['diary']->value['type']==0){?>selected<?php }?>>选择日记类型</option>
                            <option value="1" <?php if ($_smarty_tpl->tpl_vars['diary']->value['type']==1){?>selected<?php }?>>365周报</option>
                            <option value="2" <?php if ($_smarty_tpl->tpl_vars['diary']->value['type']==2){?>selected<?php }?>>365线下聚会</option>
                            <option value="3" <?php if ($_smarty_tpl->tpl_vars['diary']->value['type']==3){?>selected<?php }?>>365黑板报</option>
                            <option value="4" <?php if ($_smarty_tpl->tpl_vars['diary']->value['type']==4){?>selected<?php }?>>365明星同学</option>
                            <option value="5" <?php if ($_smarty_tpl->tpl_vars['diary']->value['type']==5){?>selected<?php }?>>其他</option>
                        </select>
                    <?php }?>
                    <div class="note-sync-wrap">
                        <span class="note-sync-text">同步到:</span>
                        <a href="#" title="sina" class="login-sina">新浪登录</a>
                        <a href="#" title="dou" class="login-dou">豆瓣登录</a>
                    </div>
                </div>
            </div>
            <input type ="hidden" name ='date' value ="<?php echo $_smarty_tpl->tpl_vars['diary']->value['date'];?>
"/>
            <input type ="hidden" name ='date_ts' value ="<?php echo $_smarty_tpl->tpl_vars['diary']->value['date_ts'];?>
"/>
            <input type ="hidden" name ='diary_id' value ="<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
"/>
            <input type ="hidden" name ='diary_ext_id' value ="<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_ext_id'];?>
"/>
        </form>
    </div>
    <div class="footer-load"></div>
    <script src="/statics/js/jquery-1.10.1.min.js"></script>
    <script src="/statics/js/temp-ajax-load.js"></script>
    <script src="/statics/js/common.js"></script>
</body>
</html><?php }} ?>