(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.init();
    }

    Plugin.prototype = {
        init:        function ()
                     {
                         var parent = this;
                         this.initCompare();
                         this.initFilter();
                     },
        initCompare: function ()
                     {
                         var parent = this;
                         $(parent.settings.compare.container).click(function (e)
                         {
                             if($(event.target).is('a') || ($(event.target).is('i') && $(event.target).parent().is('a')))
                             {
                                 e.preventDefault();
                                 $(parent.settings.modal.content).html(parent.settings.modal.default);
                                 $(parent.settings.modal.container).foundation('reveal', 'open');
                                 $.ajax
                                 ({
                                     type:    'POST',
                                     url:     $(this).attr('data-url'),
                                     data:    {
                                         id: $(this).attr('data-id')
                                     },
                                     success: function (data)
                                              {
                                                  $(parent.settings.modal.content).html(data);
                                                  $(parent.settings.modal.container).foundation('reveal', 'open');
                                                  parent.initFilter();
                                              }
                                 });
                             }
                         });
                     },
        initFilter:  function ()
                     {
                         var parent = this;
                         $(parent.settings.filter.container).click(function (e)
                         {
                             if($(this).hasClass('disabled'))
                             {
                                 $(this).removeClass('disabled');
                                 if($(this).attr('data-filter') == 1)
                                 {
                                     $(parent.settings.filter.status1).fadeIn();
                                 }
                                 else
                                 {
                                     $(parent.settings.filter.status0).fadeIn();
                                 }
                             }
                             else
                             {
                                 $(this).addClass('disabled');
                                 if($(this).attr('data-filter') == 1)
                                 {
                                     $(parent.settings.filter.status1).fadeOut();
                                 }
                                 else
                                 {
                                     $(parent.settings.filter.status0).fadeOut();
                                 }
                             }
                         });
                     }
    };
    $.fn.historic = function (options)
    {
        var settings = $.extend
        ({
            modal:   {
                container: '#historicModal',
                content:   '#historicModalContent',
                default:   "<i class='fa fa-spin fa-refresh'></i>"
            },
            compare: {
                container: '.historic a[data-compare]'
            },
            filter:  {
                container: '.historic div[data-filter]',
                status0:   '.historic .body.warning',
                status1:   '.historic .body:not(.warning)'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));