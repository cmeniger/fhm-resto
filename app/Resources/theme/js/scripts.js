(function ($) {
    "use strict";
    var Resto = {
        init: function () {
            this.moveScroll();
            this.parallaxImage();
            this.tabListMove();
            this.sliderBox();
            this.resizeScreen();
        },
        parallaxImage: function () {
            if ($('.image-banner').length) {
                var image = $('.image-banner').data('image-src');
                $('.image-banner').parallax({imageSrc: image,speed: 0.8});
            }
            if ($('.parallax-image-offers').length) {
                var imageOffer = $('.parallax-image-offers').data('image-src');
                $('.parallax-image-offers').parallax({imageSrc: imageOffer});
            }
            if ($('.parallax-image-shop').length) {
                var imageShop = $('.parallax-image-shop').data('image-src');
                $('.parallax-image-shop').parallax({imageSrc: imageShop,speed: 0.6});
            }
        },
        moveScroll: function () {
            var self = this;
            $(window).scroll(function () {
                var posTop = $(this).scrollTop();
                if (posTop > 0) {
                    $('#header').addClass('sticky');
                }
                else {
                    $('#header').removeClass('sticky');
                }
            });
        },
        sliderBox: function() {
            if($('.syn-slider').length) {
                var $status = $('.slider-for');
                var $slickElement = $('.slider-for');

                $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
                    //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
                    var i = (currentSlide ? currentSlide : 0) + 1;
                    if(!$('.slider-for .pager-right').length) {
                        var $statusPager = $status.append('<div class="pager-right"></div>');
                        var $statusPager = $status.prepend('<div class="pager-left"></div>');
                    }
                    var prevIndex = (i-1)<=0?slick.slideCount:i-1;
                    var nextIndex = (i+1)>slick.slideCount?1:i+1;
                    $('.slider-for .pager-left').text(prevIndex + '/' + slick.slideCount);
                    $('.slider-for .pager-right').text(nextIndex + '/' + slick.slideCount);
                });
                $('.syn-slider').slick({
                    infinite: true,
                    autoplay: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    asNavFor: '.slider-for',
                    dots: false,
                    arrows: false,
                    centerMode: true,
                    variableWidth: true,
                    focusOnSelect: true
                });
                $('.slider-for').slick({
                    infinite: true,
                    autoplay: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    fade: false,
                    asNavFor: '.syn-slider'
                });
            }
        },
        tabListMove: function() {
            var tabIndex = 0;
            $('.tabs-list li').click(function(e) {
                e.preventDefault();
                tabIndex = $(this).index();
                $('.tabs-list li').removeClass('active');
                $(this).addClass('active');
                $('.tabs-panel-wrap .tabs-panel').removeClass('active');
                $('.tabs-panel-wrap .tabs-panel').eq(tabIndex).addClass('active');
            });
        },
        resizeScreen: function() {
            $(window).on("load resize",function(e){
                if($('.flip-box').length) {
                    $('.flip-box .flip-back').show();
                    $('.flip-box').each(function () {
                        var imageHeight = $(this).find('img').height();
                        $('.flip-box').css('height', imageHeight);
                    });
                    $('.flip-box').flip({
                        trigger: 'hover',
                        reverse: true
                    });
                }
            });
        }
    }
    $(document).ready(function () {
        Resto.init();
    });
})(jQuery);