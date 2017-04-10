(function ($) {
    "use strict";
    var Resto = {
        init: function () {
            this.moveScroll();
            this.parallaxImage();
            this.tabListMove();
            this.sliderBox();
            this.resizeScreen();
            this.menuBox();
            this.scrollDocument();
        },
        menuBox: function() {
            $('.menu-icon-wrap .menu-icon').click(function(e) {
                e.preventDefault();
                $('.device-menu-wrap').toggleClass('expand');
                $('.wrap-mask').fadeToggle();
            });
            $('.close-menu,.wrap-mask').click(function(e) {
                e.preventDefault();
                $('.device-menu-wrap').removeClass('expand');
                $('.wrap-mask').fadeOut();
            });
            $('.sidr-dropdown-toggler').click(function(e) {
                e.preventDefault();
                $(this).toggleClass('expand');
                $(this).next().slideToggle();
            });
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
                $('.tabs-panel-wrap .tabs-panel ul').removeAttr('style');
                $(this).addClass('active');
                $('.tabs-panel-wrap .tabs-panel').removeClass('active');
                $('.tabs-panel-wrap .tabs-panel').eq(tabIndex).addClass('active');
            });
            $('.header-tab').click(function(e) {
                var self = this;
                e.preventDefault();
                if(!$(this).hasClass('active')) {
                    var excludeItem = $('.tabs-panel-wrap .tabs-panel .header-tab').not(this);
                    $('.tabs-list li').removeClass('active');
                    var currentIndex = $(this).parent('.tabs-panel').index();
                    $('.tabs-list li').eq(currentIndex).addClass('active');
                    excludeItem.removeClass('active');
                    excludeItem.parent().removeClass('active');
                    excludeItem.next().slideUp();

                    $(this).next().slideDown('slow', function() {
                        $(self).parents('.tabs-panel').addClass('active');
                    });
                    $(this).addClass('active');
                }
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
        },
        scrollDocument: function () {
            $(window).scroll(function () {
                if ($('.vc-row-wrapper .info').length) {
                    if ($('.vc-row-wrapper .info').customIsOnScreen()) { // if target element is visible on screen after DOM loaded
                        $('.vc-row-wrapper .info').addClass('onscreen');
                    }
                }
            });
        }
    }
    $(document).ready(function () {
        Resto.init();
    });

    $.fn.customIsOnScreen = function () {
        var win = $(window);
        var viewport = {
            top: win.scrollTop(),
            left: win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();
        var bounds = this.offset();
        bounds.right = bounds.left + this.outerWidth();
        bounds.bottom = bounds.top + this.outerHeight();
        return (!(viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    };
})(jQuery);