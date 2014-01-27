<?php /* Smarty version Smarty-3.1.3, created on 2014-01-22 15:34:00
         compiled from "/home/sid/project/php/github/i365day/application/views/diary/detail.phtml" */ ?>
<?php /*%%SmartyHeaderCode:158886191952d4f17e6ab544-21153491%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7f57e6c68b7c94a5014b463cffad20b52913b9ac' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/diary/detail.phtml',
      1 => 1390376038,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '158886191952d4f17e6ab544-21153491',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52d4f17e7e3d9',
  'variables' => 
  array (
    'diary' => 0,
    'user' => 0,
    'tag' => 0,
    'is_related' => 0,
    'diary_days' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d4f17e7e3d9')) {function content_52d4f17e7e3d9($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_my_days')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.my_days.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
?><!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
--365每天记</title>
        <link rel="stylesheet" href="/statics/css/common.css">
        <!--[if lt IE 9]>
        <script src="/statics/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="header-load"></div>
        <div class="main clearfix">
            <section class="article-detail">
                <article class="article-content-wrap">
                    <div class="article-title-wrap">
                        <div class="note-date-label">
                            <strong class="note-date-text">DAY</strong>
                            <em class="note-date-days"><?php echo smarty_modifier_my_days($_smarty_tpl->tpl_vars['diary']->value['date_ts']);?>
</em>
                        </div>
                        <div class="article-title-detail">
                            <h1 class="article-title"><?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
</h1>
                            <div class="article-info">
                                <a href="#" class="article-author" title="<?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
</a>
                                <span class="article-report-from">发表于</span>
                                <time class="article-time"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['diary']->value['create_time'],"%Y-%m-%d %H:%M:%S");?>
</time>
                            </div>
                        </div>
                    </div>
                    <div class="article-content">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['diary']->value['pic'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['diary']->value['pic_desc'];?>
" class="article-img">
                        <p><?php echo $_smarty_tpl->tpl_vars['diary']->value['content'];?>
</p>
                        <div class="article-label-wrap">
                            <?php  $_smarty_tpl->tpl_vars['tag'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tag']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['diary']->value['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->key => $_smarty_tpl->tpl_vars['tag']->value){
$_smarty_tpl->tpl_vars['tag']->_loop = true;
?>
                            <a href="#" class="article-label" title="<?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
">#<?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</a>
                            <?php } ?>
                        </div>
                        <a href="#" class="article-icon-zan <?php if (!$_smarty_tpl->tpl_vars['is_related']->value){?>disable<?php }?> clearword"  title="赞一个" alt="<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
" author="<?php echo $_smarty_tpl->tpl_vars['diary']->value['user_id'];?>
" author-name="<?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
" diary-title="<?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
">赞一个</a>
                        <a href="#" class="article-icon-other clearword disable" title="分享">分享</a>
                        <div class="article-opearte">
                            <a href="/diary/edit/diary_id/<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
" class="article-opearte-edit" title="编辑">编辑</a>
                            <a href="#" class="article-opearte-delete" title="删除">删除</a>
                            <?php if ($_smarty_tpl->tpl_vars['diary']->value['visibility']){?><a href="#" class="article-opearte-author" title="仅自己可见">仅自己可见</a><?php }?>
                        </div>
                         <!-- Baidu Button BEGIN -->
                        <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare article-share">
                            <a class="bds_tsina"></a>
                            <a class="bds_douban"></a>
                            <a class="bds_qzone"></a>
                            <a class="bds_tqq"></a>
                            <a class="bds_renren"></a>
                            <span class="bds_more"></span>
                        </div>
                        <!-- Baidu Button END -->
                    </div>
                </article>
                <form action="/comment/docreate" method="post">
                    <input type="hidden" name="diary_id" id="diary_id" value ="<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
"/>
                    <input type="hidden" name="follow_id" id="follow_id"/>
                    <input type="hidden" name="user_id" value="<?php echo $_smarty_tpl->tpl_vars['diary']->value['user_id'];?>
">
                    <input type="hidden" name="diary_title" value="<?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
">

                    <div class="article-leave-message">
                        <h2 class="article-message-title">评论</h2>
                        <textarea class="comment-text" name="content" id="content" placeholder="写评论吧"></textarea>
                        <div class="comment-btn-wrap">
                            <button class="comment-btn" type="submit" title="评论">评论</button>
                        </div>
                        <div class="comment-load"></div>
                    </div>
                </form>
            </section>
            <section class="article-aside">
                <aside class="article-author-info">
                    <div class="clearfix">
                        <img src="/statics/images/home_img_head.jpg" alt="头像" width="90" height="90" class="head-sculpture">
                        <a class="username" href="myhome.html" title="<?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['user']->value['nick_name'];?>
</a>
                        <p class="detail-days"><?php echo $_smarty_tpl->tpl_vars['diary_days']->value;?>
个日子</p>
                        <p class="user-info-guanzhu">
                            <a href="#" class="guanzhu" title="关注他">关注他</a>
                            <a href="#" class="quxiao-guanzhu hide" title="取消关注">取消关注</a>
                        </p>
                    </div>
                    <p class="article-user-description"><?php echo $_smarty_tpl->tpl_vars['user']->value['intro'];?>
</p>
                    <p class="usercity"><?php echo $_smarty_tpl->tpl_vars['user']->value['city'];?>
</p><p class="device">使用<?php echo $_smarty_tpl->tpl_vars['user']->value['camera_brand'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['camera_model'];?>
</p>
                </aside>
                <aside class="last-year">
                    <h2 class="all-hot-title">去年今日</h2>
                    <div class="like-load"></div>
                </aside>
                <aside class="last-prev-year">
                    <h2 class="all-hot-title">前年今日</h2>
                    <div class="like-load"></div>
                </aside>
            </section>
        </div>
        <div class="footer-load"></div>
        <div class="common-dialog j-delete-confirm">
            <div class="common-dialog-title">提示</div>
            <p class="common-dialog-text">确定要删除吗？</p>
            <a href="#" class="common-dialog-cancel j-delete-cancel" title="取消">取消</a>
            <a href="/diary/del/diary_id/<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
/diary_ext_id/<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_ext_id'];?>
" class="common-dialog-ok j-delete-ok" title="确定">确定</a>
            <a href="#" class="common-dialog-close clearword j-delete-close" title="关闭">关闭</a>
        </div>
        <script src="/statics/js/jquery-1.10.1.min.js"></script>
        <script src="/statics/js/temp-ajax-load.js"></script>
        <script src="/statics/js/common.js"></script>
        <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=5968326" ></script>
        <script type="text/javascript" id="bdshell_js"></script>
        <script type="text/javascript">
        document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
        </script>
    </body>
</html><?php }} ?>