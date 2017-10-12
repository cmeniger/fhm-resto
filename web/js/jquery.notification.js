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
                             if(!parent.settings.project.construct && !parent.settings.project.maintenance)
                             {
                                 $('body').append("<div id='" + parent.settings.modal.container + "' class='reveal-modal' data-reveal aria-labelledby='modalTitle' aria-hidden='true' role='dialog'><div id='" + parent.settings.modal.content + "'></div><a class='close-reveal-modal' aria-label='Close'>&#215;</a></div>");
                                 this.initAction();
                                 this.initModal();
                                 this.initCounter();
                             }
                         },
            initAction:  function ()
                         {
                             var parent = this;
                             $(parent.settings.action.container).unbind('click').click(function (e)
                                 {
                                     e.preventDefault();
                                     $.ajax
                                     ({
                                         type:    'POST',
                                         url:     $(this).attr('data-url'),
                                         data:    {},
                                         success: function (data)
                                                  {
                                                      $('#' + parent.settings.modal.content).html(data);
                                                      parent.initModal();
                                                      parent.initAction();
                                                  }
                                     });
                                 }
                             );
                         },
            initModal:   function ()
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
                                                      $('#' + parent.settings.modal.content).html(data);
                                                      $('#' + parent.settings.modal.container).foundation('reveal', 'open');
                                                      parent.initModal();
                                                      parent.initAction();
                                                  }
                                     });
                                 }
                             );
                         },
            initCounter: function ()
                         {
                             var parent = this;
                             setInterval(function match()
                             {
                                 $.ajax
                                 ({
                                     type:    'POST',
                                     url:     parent.settings.counter.url,
                                     data:    {},
                                     success: function (data)
                                              {
                                                  $(parent.settings.counter.container).each(function ()
                                                  {
                                                      if(data.count == 0)
                                                      {
                                                          $(this).removeClass('new').html(data.count);
                                                      }
                                                      else
                                                      {
                                                          $(this).addClass('new').html(data.count);
                                                      }
                                                  });
                                              }
                                 });
                             }, parent.settings.counter.time);
                         }
        };
        $.fn.notification = function (options)
        {
            var settings = $.extend
            ({
                project: {
                    maintenance: false,
                    construct:   false
                },
                modal:   {
                    container: 'notificationModal',
                    content:   'notificationModalContent',
                    action:    '.notification a[data-modal]'
                },
                action:  {
                    container: '.notification a[data-action]'
                },
                counter: {
                    container: '.notification [data-counter]',
                    url:       '/api/notification/counter/number',
                    time:      10000
                }
            }, options);
            new Plugin(settings);
        };
    }
    (jQuery, window, window.document)
);