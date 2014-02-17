//载入header
$('.header-load').load('/lib/header .header');
//载入home
$('.home-load').load('/lib/home .home-info-wrap');
//载入paging
//$('.paging-load').load('/lib/paging .article-paging-box');
//载入leavingmsg
$('.leavingmsg-load').load('/lib/leavingmsg .leavingmsg-detail');
//载入comment
var dirayId = $('#diary_id').val();
$('.comment-load').load('/lib/comment/diary_id/'+ dirayId +' .comment-detail');
//载入footer
$('.footer-load').load('/lib/footer .footer');