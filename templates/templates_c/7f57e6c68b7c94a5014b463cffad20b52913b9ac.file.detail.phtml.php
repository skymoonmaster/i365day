<?php /* Smarty version Smarty-3.1.3, created on 2013-12-25 13:31:36
         compiled from "/home/sid/project/php/github/i365day/application/views/diary/detail.phtml" */ ?>
<?php /*%%SmartyHeaderCode:137123699052ba6d05b20c24-32097323%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7f57e6c68b7c94a5014b463cffad20b52913b9ac' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/diary/detail.phtml',
      1 => 1387949494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '137123699052ba6d05b20c24-32097323',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52ba6d05e61d2',
  'variables' => 
  array (
    'diary' => 0,
    'first_date_ts' => 0,
    'user' => 0,
    'tag' => 0,
    'duration' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52ba6d05e61d2')) {function content_52ba6d05e61d2($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
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
                            <em class="note-date-days"><?php echo ($_smarty_tpl->tpl_vars['diary']->value['date_ts']-$_smarty_tpl->tpl_vars['first_date_ts']->value)/86400+1;?>
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
                        <a href="#" class="article-icon-zan clearword" title="赞一个" alt="<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
">赞一个</a>
                        <a href="#" class="article-icon-other clearword disable" title="分享">分享</a>
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
                        <p class="detail-days"><?php echo $_smarty_tpl->tpl_vars['duration']->value;?>
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