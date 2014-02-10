<?php /* Smarty version Smarty-3.1.3, created on 2014-01-20 14:39:13
         compiled from "/home/sid/project/php/github/i365day/application/views/lib/paging.phtml" */ ?>
<?php /*%%SmartyHeaderCode:12009653052dcc491434b44-10497214%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '91bf63a4bc33fb47ce77131d1544d597e16f28e0' => 
    array (
      0 => '/home/sid/project/php/github/i365day/application/views/lib/paging.phtml',
      1 => 1388376951,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12009653052dcc491434b44-10497214',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_52dcc49144180',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52dcc49144180')) {function content_52dcc49144180($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/statics/css/common.css">
</head>
<body>
    <div class="article-paging-box">
        <ul class="article-paging-wrap clearfix">
            <li class="article-paging-item"><a href="#" title="上一页">&lt上一页</a></li>
            <li class="article-paging-item current"><a href="#" title="第1页">1</a></li>
            <li class="article-paging-item"><a href="#" title="第2页">2</a></li>
            <li class="article-paging-item"><a href="#" title="第3页">3</a></li>
            <li class="article-paging-item"><a href="#" title="第4页">4</a></li>
            <li class="article-paging-item">...</li>
            <li class="article-paging-item"><a href="#" title="下一页">下一页&gt</a></li>
        </ul>
    </div>
</body>
</html><?php }} ?>