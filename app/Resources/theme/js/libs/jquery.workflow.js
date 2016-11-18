(function ($, window, document)
    {
        function Plugin(settings)
        {
            this.settings = settings;
            this.init();
        }

        Plugin.prototype = {
            init:       function ()
                        {
                            var parent = this;
                            this.initAnchor();
                            this.initModal();
                            this.initAction();
                            this.initFilter();
                            this.initForm();
                        },
            initAnchor: function ()
                        {
                            var parent = this;
                            $(parent.settings.anchor.container).unbind('click').click(function (e)
                                {
                                    e.preventDefault();
                                    if($(this).hasClass('active'))
                                    {
                                        $(parent.settings.anchor.container).removeClass('active');
                                        $('.task-anchor').hide();
                                        $('.task').css("opacity", 1);
                                        return false;
                                    }
                                    else
                                    {
                                        var $anchor = $('.task-anchor[data-id=' + $(this).attr('data-id') + ']');
                                        var parents = $anchor.attr('data-parent').split(',');
                                        var sons = $anchor.attr('data-son').split(',');
                                        // Reset
                                        $(parent.settings.anchor.container).removeClass('active');
                                        $('.task-anchor').hide();
                                        $('.task').css("opacity", 0.3);
                                        // Current
                                        $(this).addClass('active');
                                        $anchor.removeClass('parent').removeClass('son').show();
                                        $('.task[data-id=' + $(this).attr('data-id') + ']').fadeTo("fast", 1);
                                        // Parents
                                        for(i = 0; i < parents.length; i++)
                                        {
                                            if(parents[i] != '')
                                            {
                                                $('.task[data-id=' + parents[i] + ']').css("opacity", 1);
                                                $('.task-anchor[data-id=' + parents[i] + ']').removeClass('son').addClass('parent').show();
                                            }
                                        }
                                        // Sons
                                        for(i = 0; i < sons.length; i++)
                                        {
                                            if(sons[i] != '')
                                            {
                                                $('.task[data-id=' + sons[i] + ']').css("opacity", 1);
                                                $('.task-anchor[data-id=' + sons[i] + ']').removeClass('parent').addClass('son').show();
                                            }
                                        }
                                    }
                                }
                            );
                        },
            initModal:  function ()
                        {
                            var parent = this;
                            $(parent.settings.modal.action).unbind('click').click(function (e)
                                {
                                    e.preventDefault();
                                    $.ajax
                                    ({
                                        type:    'POST',
                                        url:     $(this).attr('data-url'),
                                        data:    {},
                                        success: function (data)
                                                 {
                                                     $(window).scrollTop(0);
                                                     $(parent.settings.modal.content).html(data);
                                                     $(document).on('opened.fndtn.reveal', '[data-reveal]', function ()
                                                     {
                                                         if(typeof modalOpen != 'undefined')
                                                         {
                                                             modalOpen();
                                                         }
                                                     });
                                                     $(document).on('closed.fndtn.reveal', '[data-reveal]', function ()
                                                     {
                                                         if(typeof modalClose != 'undefined')
                                                         {
                                                             modalClose();
                                                         }
                                                     });
                                                     $(parent.settings.modal.container).foundation('reveal', 'open');
                                                     parent.initModal();
                                                     parent.initFilter();
                                                     parent.initForm();
                                                 }
                                    });
                                }
                            );
                        },
            initAction: function ()
                        {
                            var parent = this;
                            $(parent.settings.action.container).unbind('click').click(function (e)
                                {
                                    e.preventDefault();
                                    var $current = $(this);
                                    $.ajax
                                    ({
                                        type:    'POST',
                                        url:     $(this).attr('data-url'),
                                        data:    {},
                                        success: function (data)
                                                 {
                                                     if($current.attr('data-step'))
                                                     {
                                                         $.ajax
                                                         ({
                                                             type:    'POST',
                                                             url:     $current.attr('data-step'),
                                                             data:    {},
                                                             success: function (data)
                                                                      {
                                                                          $(parent.settings.action.step).fadeToggle(400, "linear", function ()
                                                                          {
                                                                              $(parent.settings.action.step).html(data).fadeToggle(400, "linear");
                                                                              parent.initModal();
                                                                              parent.initAction();
                                                                          });
                                                                      }
                                                         });
                                                     }
                                                     else
                                                     {
                                                         $(parent.settings.action.data).fadeToggle(400, "linear", function ()
                                                         {
                                                             $(parent.settings.action.data).html(parent.getHtml(data, parent.settings.action.data)).fadeToggle(400, "linear");
                                                             parent.initAnchor();
                                                             parent.initModal();
                                                             parent.initAction();
                                                         });
                                                     }
                                                 }
                                    });
                                }
                            );
                        },
            initFilter: function ()
                        {
                            var parent = this;
                            $(parent.settings.filter.container).unbind('click').click(function (e)
                                {
                                    e.preventDefault();
                                    if($(this).hasClass('deactivate'))
                                    {
                                        $(this).removeClass('deactivate');
                                        $('div[data-log=' + $(this).attr('data-filter') + ']').fadeIn();
                                    }
                                    else
                                    {
                                        $(this).addClass('deactivate');
                                        $('div[data-log=' + $(this).attr('data-filter') + ']').fadeOut();
                                    }
                                }
                            );
                        },
            initForm:   function ()
                        {
                            var parent = this;
                            $(parent.settings.form.container).submit(function (e)
                                {
                                    e.preventDefault();
                                    $.ajax
                                    ({
                                        type:    'POST',
                                        url:     $(this).attr('action'),
                                        data:    $(this).serialize(),
                                        success: function (data)
                                                 {
                                                     $(parent.settings.modal.content).fadeToggle(400, "linear", function ()
                                                     {
                                                         if(typeof modalClose != 'undefined')
                                                         {
                                                             modalClose();
                                                         }
                                                         $(parent.settings.modal.content).html(data).fadeToggle(400, "linear", function ()
                                                         {
                                                             if(typeof modalOpen != 'undefined')
                                                             {
                                                                 modalOpen();
                                                             }
                                                         });
                                                         parent.initModal();
                                                         parent.initFilter();
                                                         parent.initForm();
                                                     });
                                                 }
                                    });
                                }
                            );
                        },
            refresh:    function ()
                        {
                            var parent = this;
                        },
            getHtml:    function (data, selector)
                        {
                            var parent = this;
                            var $data = $($.parseHTML(data));
                            return $data.filter(selector).add($data.find(selector)).html();
                        }
        }
        ;
        $.fn.workflow = function (options)
        {
            var settings = $.extend
            ({
                container: '.workflow',
                modal:     {
                    container: '#wfModal',
                    content:   '#wfModalContent',
                    action:    '.workflow a[data-modal]'
                },
                anchor:    {
                    container: '.workflow a[data-anchor]'
                },
                action:    {
                    container: '.workflow a[data-action]',
                    data:      '#wfCanvas',
                    step:      '#wfStep'
                },
                filter:    {
                    container: '.workflow div[data-filter]'
                },
                form:      {
                    container: '.workflow form:not(.dropzone)'
                }
            }, options);
            new Plugin(settings);
        };
    }
    (jQuery, window, window.document)
)
;