(function ($)
{
    "use strict";
    var Project = {
        init:           function ()
                        {
                            this.menuSticky();
                            this.menuSide();
                            this.inView();
                            this.parallaxImage();
                            this.bannerHeight();
                            // this.tabListMove();
                            // this.sliderBox();
                            // this.resizeScreen();
                            // this.clickInputFiel();
                        },
        menuSticky:     function ()
                        {
                            $(window).scroll(function ()
                            {
                                var posTop = $(this).scrollTop();
                                if(posTop > 0)
                                {
                                    $('#header').addClass('sticky');
                                    $('#anchor-top').show();
                                }
                                else
                                {
                                    $('#header').removeClass('sticky');
                                    $('#anchor-top').hide();
                                }
                            });
                        },
        menuSide:       function ()
                        {
                            $('.menu-icon a').click(function (e)
                            {
                                e.preventDefault();
                                $('.menu-side-content').toggleClass('expand');
                                $('.menu-side-overlay').fadeToggle();
                            });
                            $('.menu-side-overlay, .menu-side-content a').click(function (e)
                            {
                                e.preventDefault();
                                $('.menu-side-content').removeClass('expand');
                                $('.menu-side-overlay').fadeOut();
                            });
                        },
        inView:         function ()
                        {
                            $(".row-wrapper .title h2")
                                .on("inViewBegin", function ()
                                {
                                    $(this).addClass("animated").addClass("bounceInUp");
                                });
                        },
        parallaxImage:  function ()
                        {
                            if($('.parallax-image-top').length)
                            {
                                $('.parallax-image-top').parallax(
                                    {
                                        imageSrc: $('.parallax-image-top').data('image-src'),
                                        speed:    0.8
                                    });
                            }
                            if($('.parallax-image-card').length)
                            {
                                $('.parallax-image-card').parallax(
                                    {
                                        imageSrc: $('.parallax-image-card').data('image-src')
                                    });
                            }
                            if($('.parallax-image-contact').length)
                            {
                                $('.parallax-image-contact').parallax(
                                    {
                                        imageSrc: $('.parallax-image-contact').data('image-src'),
                                        speed:    0.6
                                    });
                            }
                        },
        bannerHeight:   function ()
                        {
                            var windowHeight = $(window).height();
                            $('.banner-wrap').css('height', windowHeight);
                            $('.parallax-image-banner').css('min-height', windowHeight);
                        },
        sliderBox:      function ()
                        {
                            if($('.syn-slider').length)
                            {
                                var $status = $('.slider-for');
                                var $slickElement = $('.slider-for');
                                $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide)
                                {
                                    //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
                                    var i = (currentSlide ? currentSlide : 0) + 1;
                                    if(!$('.slider-for .pager-right').length)
                                    {
                                        var $statusPager = $status.append('<div class="pager-right"></div>');
                                        var $statusPager = $status.prepend('<div class="pager-left"></div>');
                                    }
                                    var prevIndex = (i - 1) <= 0 ? slick.slideCount : i - 1;
                                    var nextIndex = (i + 1) > slick.slideCount ? 1 : i + 1;
                                    $('.slider-for .pager-left').text(prevIndex + '/' + slick.slideCount);
                                    $('.slider-for .pager-right').text(nextIndex + '/' + slick.slideCount);
                                });
                                $('.syn-slider').slick({
                                    infinite:       true,
                                    autoplay:       true,
                                    slidesToShow:   1,
                                    slidesToScroll: 1,
                                    asNavFor:       '.slider-for',
                                    dots:           false,
                                    arrows:         false,
                                    centerMode:     true,
                                    variableWidth:  true,
                                    focusOnSelect:  true
                                });
                                $('.slider-for').slick({
                                    infinite:       true,
                                    autoplay:       true,
                                    slidesToShow:   1,
                                    slidesToScroll: 1,
                                    arrows:         true,
                                    fade:           false,
                                    asNavFor:       '.syn-slider'
                                });
                            }
                        },
        tabListMove:    function ()
                        {
                            var tabIndex = 0;
                            $('.tabs-list li').click(function (e)
                            {
                                e.preventDefault();
                                tabIndex = $(this).index();
                                $('.tabs-list li').removeClass('active');
                                $('.tabs-panel-wrap .tabs-panel ul').removeAttr('style');
                                $(this).addClass('active');
                                $('.tabs-panel-wrap .tabs-panel, .tabs-panel-wrap .tabs-panel .header-tab').removeClass('active');
                                $('.tabs-panel-wrap .tabs-panel').eq(tabIndex).addClass('active');
                                $('.tabs-panel-wrap .tabs-panel').eq(tabIndex).find('.header-tab').addClass('active');
                            });
                            $('.header-tab').click(function (e)
                            {
                                var self = this;
                                e.preventDefault();
                                if(!$(this).hasClass('active'))
                                {
                                    var excludeItem = $('.tabs-panel-wrap .tabs-panel .header-tab').not(this);
                                    $('.tabs-list li').removeClass('active');
                                    var currentIndex = $(this).parent('.tabs-panel').index();
                                    $('.tabs-list li').eq(currentIndex).addClass('active');
                                    excludeItem.removeClass('active');
                                    excludeItem.parent().removeClass('active');
                                    excludeItem.next().slideUp();
                                    $(this).next().slideDown('slow', function ()
                                    {
                                        $(self).parents('.tabs-panel').addClass('active');
                                    });
                                    $(this).addClass('active');
                                }
                            });
                        },
        resizeScreen:   function ()
                        {
                            var self = this;
                            $(window).on("load resize", function (e)
                            {
                                if($('.flip-box').length)
                                {
                                    $('.flip-box .flip-back').show();
                                    $('.flip-box').each(function ()
                                    {
                                        var imageHeight = $(this).find('img').height();
                                        $('.flip-box').css('height', imageHeight);
                                    });
                                    $('.flip-box').flip({
                                        trigger: 'hover',
                                        reverse: true
                                    });
                                }
                                self.bannerHeight();
                            });
                        },
        clickInputFiel: function ()
                        {
                            var placeholderText = '';
                            $(".form-box .input-border").click(function ()
                            {
                                $(this).parents('.border-input').addClass('border-bottom');
                                placeholderText = $(this).attr('placeholder');
                                $(this).removeAttr('placeholder');
                            });
                            $(".form-box .input-border").blur(function ()
                            {
                                $(this).parents('.border-input').removeClass('border-bottom');
                                $(this).attr('placeholder', placeholderText);
                            });
                        }
    };
    $(document).ready(function ()
    {
        Project.init();
    });
})(jQuery);