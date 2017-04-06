(function ($) {
    "use strict";
    var Resto = {
        init: function () {
            this.moveScroll();
            this.parallaxImage();
            this.tabListMove();
            this.sliderBox();
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
                $('.syn-slider').slick({
                    infinite: true,
                    autoplay: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    asNavFor: '.slider-for',
                    dots: false,
                    arrows: true,
                    centerMode: true,
                    variableWidth: true,
                    focusOnSelect: true
                });
                $('.slider-for').slick({
                    infinite: true,
                    autoplay: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
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

        }
    }

    $(document).ready(function () {
        Resto.init();
    });
})(jQuery);