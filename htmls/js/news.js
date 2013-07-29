(function($){
    //news banner crousel
    $('.news-carousel-control-item').on('mouseover',function(e){
        e.preventDefault();
        var num = $(this).index();
        changeCarousel(num);
    });

    function changeCarousel(num){
        $('.news-carousel-item:visible').stop().fadeOut(600);
        $('.news-carousel-item').eq(num).fadeIn(600);
        $('.news-carousel-control .current').removeClass('current');
        $('.news-carousel-control-item').eq(num).addClass('current');
    }

    var carouselTimeId,
        carouselSpan = 5000;
    
    function autoChangeCarousel(){
        clearTimeout(carouselTimeId);
        var num = $('.news-carousel-control .current').index() + 1,
            len = $('.news-carousel-control-item').length;
        if(num >= len){
            num = 0;
        }
        changeCarousel(num);
        carouselTimeId = window.setTimeout(autoChangeCarousel, carouselSpan);
    }

    carouselTimeId = window.setTimeout(autoChangeCarousel, carouselSpan);

    $('.news-banner').on('mouseenter',function(){
        clearTimeout(carouselTimeId);
    }).on('mouseleave',function(){
        carouselTimeId = window.setTimeout(autoChangeCarousel, carouselSpan);
    });

})(jQuery);