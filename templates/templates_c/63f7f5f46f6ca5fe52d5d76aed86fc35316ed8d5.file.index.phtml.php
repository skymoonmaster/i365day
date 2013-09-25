<?php /* Smarty version Smarty-3.1.3, created on 2013-09-25 11:30:29
         compiled from "/home/sid/project/php/cngtotools/trunk/i365day/application/views/setting/index.phtml" */ ?>
<?php /*%%SmartyHeaderCode:1922317195524258d52e9214-50376844%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63f7f5f46f6ca5fe52d5d76aed86fc35316ed8d5' => 
    array (
      0 => '/home/sid/project/php/cngtotools/trunk/i365day/application/views/setting/index.phtml',
      1 => 1380079368,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1922317195524258d52e9214-50376844',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_524258d5326ef',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524258d5326ef')) {function content_524258d5326ef($_smarty_tpl) {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>个人设置--365每天记</title>
    <link rel="stylesheet" href="../statics/css/common.css">
    <!--[if lt IE 9]>
    <script src="../statics/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header-load"></div>
    <div class="main">
        <form action="">
            <h1 class="main-title-icon settine-title">个人设置</h1>
            <div class="setting-wrap line">
                <ul class="setting-list clearfix">
                    <li class="setting-item">
                        <h2 class="setting-item-title">头像</h2>
                        <div class="setting-item-content">
                            <div class="setting-head-wrap"></div>
                            <input type="file" class="setting-head-upload">
                            <button class="setting-head-update clearword">更新头像</button>
                            <button class="setting-head-cancel clearword">取消</button>
                        </div>
                    </li>
                    <li class="setting-item">
                        <h2 class="setting-item-title">个人信息</h2>
                        <div class="setting-item-content setting-item-info">
                            <table class="setting-form-table">
                                <tbody>
                                    <tr>
                                        <td class="setting-table-name">昵称</td>
                                        <td class="setting-table-value"><input type="text" class="setting-input" value="于蓁名字很长"></td>
                                    </tr>
                                    <tr>
                                        <td class="setting-table-name">签名</td>
                                        <td class="setting-table-value"><textarea class="setting-input setting-sign"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="setting-table-name">城市</td>
                                        <td class="setting-table-value"><input type="text" class="setting-input"></td>
                                    </tr>
                                    <tr>
                                        <td class="setting-table-name">相机</td>
                                        <td class="setting-table-value"><input type="text" class="setting-input"></td>
                                    </tr>
                                    <tr>
                                        <td class="setting-table-name">时区</td>
                                        <td class="setting-table-value">
                                            <select class="setting-time-area">
                                                <option value="8" selected>GMT+8 北京</option>
                                                <option value="8">GMT+8 北京</option>
                                                <option value="8">GMT+8 北京</option>
                                                <option value="8">GMT+8 北京</option>
                                                <option value="8">GMT+8 北京</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                    <li class="setting-item">
                        <h2 class="setting-item-title">帐号和第三方绑定</h2>
                        <div class="setting-item-content setting-item-bind">
                            <table "setting-form-table">
                                <tbody>
                                    <tr>
                                        <td class="setting-table-name">邮箱</td>
                                        <td class="setting-table-value"><input type="text" class="setting-input" value="meixiangfang@baidu.com"></td>
                                    </tr>
                                    <tr>
                                        <td class="setting-table-name">新浪微博</td>
                                        <td class="setting-table-value"><a class="setting-binded" href="#" title="fond" target="_blank">fond</a><a href="#" class="setting-cancel-bind">取消绑定</a></td>
                                    </tr>
                                    <tr>
                                        <td class="setting-table-name">豆瓣</td>
                                        <td class="setting-table-value"><a href="#" class="setting-bind" title="绑定帐号">绑定帐号</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                </ul>
                <div class="setting-submit-wrap">
                    <button class="setting-submit clearword" title="保存全部">保存全部</button>
                </div>
            </div>
        </form>
    </div>
    <div class="footer-load"></div>
    <script src="../statics/js/jquery-1.10.1.min.js"></script>
    <script src="../statics/js/temp-ajax-load.js"></script>
    <script src="../statics/js/common.js"></script>
</body>
</html><?php }} ?>