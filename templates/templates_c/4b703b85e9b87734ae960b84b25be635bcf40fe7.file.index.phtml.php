<?php /* Smarty version Smarty-3.1.3, created on 2014-01-27 14:53:11
         compiled from "/home/sid/project/php/github/i365day/application/views/news/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:200551674152e5cdb0afc2c4-31094744%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b703b85e9b87734ae960b84b25be635bcf40fe7' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/news/index.phtml',
      1 => 1390805591,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '200551674152e5cdb0afc2c4-31094744',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52e5cdb0b8ac3',
  'variables' => 
  array (
    'diary_list' => 0,
    'diary' => 0,
    'diary_type_id_to_name' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52e5cdb0b8ac3')) {function content_52e5cdb0b8ac3($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sid/project/php/github/i365day/application/library/Smarty/libs/plugins/modifier.date_format.php';
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>365新鲜事</title>
    <link rel="stylesheet" href="/statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="/statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header-load"></div>
    <div class="main clearfix">
        <section class="news-wrap">
            <div class="news-banner">
                <ul class="news-carousel">
                    <li class="news-carousel-item current"><a href="#" title="365旅途黑板报"><img src="/statics/images/news_img_banner.jpg" width="766" height="261" alt="365旅途黑板报"></a></li>
                    <li class="news-carousel-item"><a href="#" title="365旅途黑板报"><img src="/statics/images/news_img_banner.jpg" width="766" height="261" alt="365旅途黑板报"></a></li>
                    <li class="news-carousel-item"><a href="#" title="365旅途黑板报"><img src="/statics/images/news_img_banner.jpg" width="766" height="261" alt="365旅途黑板报"></a></li>
                    <li class="news-carousel-item"><a href="#" title="365旅途黑板报"><img src="/statics/images/news_img_banner.jpg" width="766" height="261" alt="365旅途黑板报"></a></li>
                </ul>
                <ul class="news-carousel-control">
                    <li class="news-carousel-control-item current"><a href="#"></a></li>
                    <li class="news-carousel-control-item"><a href="#"></a></li>
                    <li class="news-carousel-control-item"><a href="#"></a></li>
                    <li class="news-carousel-control-item"><a href="#"></a></li>
                </ul>
            </div>
            <div class="news-article">
                <?php  $_smarty_tpl->tpl_vars['diary'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['diary']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['diary_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['diary']->key => $_smarty_tpl->tpl_vars['diary']->value){
$_smarty_tpl->tpl_vars['diary']->_loop = true;
?>
                <article class="clearfix">
                    <img class="article-image" src="<?php echo $_smarty_tpl->tpl_vars['diary']->value['thumbnail'];?>
" width="320" height="210" alt="<?php echo $_smarty_tpl->tpl_vars['diary']->value['pic_desc'];?>
">
                    <div class="article-wrap">
                        <h2 class="article-title"><a href="/diary/detail/diary_id/<?php echo $_smarty_tpl->tpl_vars['diary']->value['diary_id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
"><?php echo $_smarty_tpl->tpl_vars['diary']->value['title'];?>
</a></h2>
                        <a class="article-author" href="/home/index/p/<?php echo $_smarty_tpl->tpl_vars['diary']->value['user_id'];?>
" title="fond">fond</a><span class="article-from">发表于</span><span class="article-time"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['diary']->value['update_time'],"%Y-%m-%d %H:%M");?>
</span><a class="article-type" href="#">[<?php echo $_smarty_tpl->tpl_vars['diary_type_id_to_name']->value[$_smarty_tpl->tpl_vars['diary']->value['type']];?>
]</a>
                        <p class="article-text">现在是一个大数据时代，人人嘴边都挂着数据创造价值、数据挖掘等一些热词。各公司内部也逐渐认识到数据的重要性，纷纷成立数据部门，期待数据可以真正的为业务服务。另外，也有一些专做数据服务的第三方公司不断涌现，希望能够帮助产生数据的甲方分担数据分析的担子。</p>
                    </div>
                </article>
                <?php } ?>
                <div class="paging-load"></div>
            </div>
        </section>
        <section class="news-aside">
            <aside class="big-logo-wrap">
                <img class="big-logo" src="/statics/images/news_img_biglogo.png" width="137" height="107" alt="365-logo">
                <h1>
                    <div class="big-title"><em class="big-365">365</em><strong>每天记</strong></div>
                    <strong class="big-sub-title">新鲜事</strong>
                </h1>
            </aside>
            <aside class="news-about">
                <h2 class="news-about-title">关于365</h2>
                <p>365是个“每日一片”的网络联盟，通过每天一张的照片，记录分享生活</p>
                <p>我们的口号——请看我漂亮的坚持！</p>
                <p class="news-about-tips">这是个温暖的小集体，各位玩得开心点 :)</p>
                <p class="news-about-know-wrap"><a class="news-know-about clearword" href="/about" title="了解365">了解365</a><a class="news-leave-a-message clearword" href="mailto:i365day@qq.com" title="给我们留言">给我们留言</a></p>
                <p><a class="news-subscribe clearword" href="#" title="订阅365新鲜事">订阅365新鲜事</a></p>
                <ul class="news-about-list">
                    <li class="news-about-item"><a href="#" title="365周报">365周报</a></li>
                    <li class="new-about-sep"></li>
                    <li class="news-about-item"><a href="#" title="365线下聚会">365线下聚会</a></li>
                    <li class="new-about-sep"></li>
                    <li class="news-about-item"><a href="#" title="365黑板报">365黑板报</a></li>
                    <li class="new-about-sep"></li>
                    <li class="news-about-item"><a href="#" title="365明星同学">365明星同学</a></li>
                    <li class="new-about-sep"></li>
                    <li class="news-about-item"><a href="#" title="其他">其他</a></li>
                </ul>
            </aside>
            <aside class="news-search">
                <form>
                    <input type="text" placeholder="搜索新鲜事">
                    <button class="news-search-submit clearword" type="submit" title="搜索">搜索</button>
                </form>
            </aside>
            <aside class="all-hot">
                <h2 class="all-hot-title">全站最热</h2>
                <div class="like-load"></div>
            </aside>
        </section>
    </div>
    <div class="footer-load"></div>
    <script src="/statics/js/jquery-1.10.1.min.js"></script>
    <script src="/statics/js/temp-ajax-load.js"></script>
    <script src="/statics/js/common.js"></script>
    <script src="/statics/js/news.js"></script>
</body>
</html><?php }} ?>