(function($){
    //弹窗显隐
    var dialogTime = 300;
    function displayDialog(clickElem,dialogClassName,closeELem){
        $(document).on('click',clickElem,function(e){
            e.preventDefault();
            $('<div class="mask1000"></div>').appendTo('body').width($(window).width()).height($(document).height()).fadeIn(dialogTime,function(){
            });
                $('.' + dialogClassName).fadeIn(dialogTime);
        });
        $(document).on('click',closeELem,function(e){
            e.preventDefault();
            $('.' + dialogClassName).fadeOut(dialogTime,function(){
                $('.mask1000').fadeOut(dialogTime).remove();
            });    
        });
    }
    //申请内测弹窗
    displayDialog('.apply','j-apply','.j-apply-close');
    //消息中心弹窗
    displayDialog('.menu-message,.message-num','j-message','.j-message-close');
    //登录弹窗
    displayDialog('.login a','j-login','.j-login-close');

    //申请内测表单显隐
    $('.choose input').on('click',function(){
        this.value === 'yes' ? $('.choose-item').show() : $('.choose-item').hide();
    });

    //个人信息菜单显隐
    $(document).on('mouseenter','.info-username',function(e){
        e.preventDefault();
        $('.user-menu').stop().slideDown();
    }).on('mouseleave','.info-username',function(e){
        e.preventDefault();
        $('.user-menu').stop().slideUp();
    });

    //主页年月按钮
    $('.home-date-btn').on('click',function(e){
        e.preventDefault();
        $('.home-date-wrap').toggle();
    });

    //首页轮播
    $.easing.easeOutBack = function(e, f, a, i, h, g) {
        if (g == undefined) {
            g = 1.70158;
        }
        return i * ((f = f / h - 1) * f * ((g + 1) * f + g) + 1) + a;
    };

    var indexCrouselNum = 0,
        indexCrouselLimit = $('.index-img-item').length;

    $('.main-img .previous').on('click',function(e){
        e.preventDefault();
        if(indexCrouselNum - 1 >= 0){
            indexCrousel(--indexCrouselNum,-800,400,300,1000,800);
        }
    });

    $('.main-img .next').on('click',function(e){
        e.preventDefault();
        if(indexCrouselNum + 1 < indexCrouselLimit){
            indexCrousel(++indexCrouselNum,400,350,450,800,1000);
        }
    });

    function indexCrousel(num,left,textTime,infoTime,textSpan,infoSpan){
        var text = $('.index-img-item').eq(num).find('.main-wz'),
            info = $('.index-img-item').eq(num).find('.main-img-info');
        text.css("left",left);
        info.css("left",left);
        $('.index-img-list').animate({left: - num * 560},500);
        window.setTimeout(function(){
            text.animate({left: 0},textSpan,'easeOutBack');
        },textTime)
        window.setTimeout(function(){
            info.animate({left: 0},infoSpan,'easeOutBack');
        },infoTime)
    }


    //图片本地预览
    function previewImage(obj){
        if(!document.attachEvent){
            var fileList = obj.files;
                filetype = /^image\/(?:png|gif|jpeg)$/i;
            for (var i = 0,l = fileList.length; i < l; i++) {
                var file = fileList[i];
                if (!file.type.match(filetype)) {
                    continue;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    var fileName = e.target.result;
                    $(obj).prev().attr('src',fileName);
                };
                reader.readAsDataURL(file);
            }
        }else{
            obj.select();
            obj.blur();
            var fileName=document.selection.createRange().text;
            $(obj).parent().attr('style','filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);');
            $(obj).parent().get(0).filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = fileName;
            $(obj).prev().hide();

        }
    }

    $('.note-img-upload').on('change',function(){
        $('.note-img-tips').hide();
        previewImage(this);
    });


    //主页年份切换
    var maxYear = new Date().getFullYear();
    $('.home-year-next').on('click',function(e){
        e.preventDefault();
        var year = parseInt($('.home-year-num').text()) + 1;
        if(year <= maxYear){
            $('.home-year-num').text(year + '年');
        }
    });

    $('.home-year-prev').on('click',function(e){
        e.preventDefault();
        var year = parseInt($('.home-year-num').text()) - 1;
        $('.home-year-num').text(year + '年');
    });

    $('.home-month-selectable').on('click',function(e){
        e.preventDefault();
        $('.home-month-selected').addClass('home-month-selectable').removeClass('home-month-selected')
        $(this).addClass('home-month-selected').removeClass('home-month-selectable');
    });


    /*copy*/

    $(".copy-btn").click(function(){
    if (window.clipboardData){   
        var text = $(this).parent().find(".copy-info").val();
        window.clipboardData.setData('text',text);
        alert("已成功到剪贴板");           
    }
    else{   
        alert("浏览器不支持此功能,请您手动复制"); 
    }

});
    
})(jQuery);