(function($){
    //个人信息菜单显隐
    $(document).on('mouseenter','.info-username',function(e){
        e.preventDefault();
        $('.user-menu').stop().slideDown();
    }).on('mouseleave','.info-username',function(e){
        e.preventDefault();
        $('.user-menu').stop().slideUp();
    });

    //消息中心
    var messageTime = 600;
    $(document).on('click','.menu-message,.message-num',function(e){
        e.preventDefault();
        $('<div class="message-mask"></div>').appendTo('body').width($(window).width()).height($(document).height()).fadeIn(messageTime,function(){
            $('.message').fadeIn(messageTime);
        });
    });
    $(document).on('click','.message-close',function(e){
        e.preventDefault();
        $('.message').fadeOut(messageTime,function(){
            $('.message-mask').fadeOut(messageTime).remove();
        });    
    });
})(jQuery);