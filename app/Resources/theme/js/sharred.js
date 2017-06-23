(function ($)
{
    "use strict";
    var Project = {
        init:          function ()
                       {
                           this.menuSticky();
                           this.menuSide();
                           this.menuAnchor();
                           this.inView();
                           this.parallaxImage();
                           this.bannerHeight();
                       },
        menuSticky:    function ()
                       {
                           $(window).scroll(function ()
                           {
                               var posTop = $(this).scrollTop();
                               if(posTop > 0)
                               {
                                   $('#header').addClass('sticky');
                                   $('#scroll-top').show();
                               }
                               else
                               {
                                   $('#header').removeClass('sticky');
                                   $('#scroll-top').hide();
                               }
                           });
                       },
        menuSide:      function ()
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
        menuAnchor:    function ()
                       {
                           $('#header a, #scroll-top').click(function (e)
                           {
                               var target = $(this.hash);
                               target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                               if(target.length)
                               {
                                   var $viewport = $('html,body');
                                   $viewport.animate({
                                       scrollTop: target.offset().top
                                   }, 1500, 'easeOutBounce');
                                   $viewport.bind("scroll mousedown DOMMouseScroll mousewheel keyup", function ()
                                   {
                                       $viewport.stop();
                                   });
                                   return false;
                               }
                           });
                       },
        inView:        function ()
                       {
                           $(".row-wrapper .title h2")
                               .on("inViewBegin", function ()
                               {
                                   $(this).addClass("animated").addClass("bounceInUp");
                               });
                           $("section header h2, section header h4")
                               .on("inViewBegin", function ()
                               {
                                   $(this).addClass("animated").addClass("zoomIn");
                               })
                               .on("inViewEnd", function ()
                               {
                                   $(this).removeClass("animated").removeClass("zoomIn");
                               });
                       },
        parallaxImage: function ()
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
                           if($('.parallax-image-testimony').length)
                           {
                               $('.parallax-image-testimony').parallax(
                                   {
                                       imageSrc: $('.parallax-image-testimony').data('image-src'),
                                       speed:    0.6
                                   });
                           }
                       },
        bannerHeight:  function ()
                       {
                           var windowHeight = $(window).height();
                           $('.banner-wrap').css('height', windowHeight);
                           $('.parallax-image-banner').css('min-height', windowHeight);
                       }
    };
    $(document).ready(function ()
    {
        Project.init();
    });
})(jQuery);