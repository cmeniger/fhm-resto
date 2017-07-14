(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.process = this.settings.process;
        this.post = '';
        this.init();
    }

    Plugin.prototype = {
        init:        function ()
                     {
                         var parent = this;
                         this.initProcess();
                     },
        initProcess: function ()
                     {
                         var parent = this;
                         if(parent.process.length === 0)
                         {
                             parent.initEnd();
                         }
                         else
                         {
                             parent.initStart();
                             parent.initLogs();
                         }
                     },
        initStart:   function ()
                     {
                         var parent = this;
                         $(parent.settings.container_end).fadeOut(400, "linear", function ()
                         {
                             $(parent.settings.container_start).fadeIn(400, "linear", function ()
                             {
                                 parent.next(0, true);
                             });
                         });
                     },
        initEnd:     function ()
                     {
                         var parent = this;
                         $(parent.settings.container_start).fadeOut(400, "linear", function ()
                         {
                             $(parent.settings.container_end).fadeIn(400, "linear", function ()
                             {
                                 parent.initTimer($(parent.settings.container_end + ' .timer'));
                             });
                         });
                     },
        initLogs:    function ()
                     {
                         var parent = this;
                         $(parent.settings.container_logs).fadeIn(400, "linear", function ()
                         {
                         });
                     },
        initTimer:   function ($element)
                     {
                         var parent = this;
                         var timer = $element.attr('data-time');
                         var id = 'timer-' + Math.floor(Math.random() * 1000);
                         $element.append(" <span id='" + id + "'>" + timer + "</span> s.");
                         var interval = setInterval(function ()
                         {
                             if(timer <= 0)
                             {
                                 clearInterval(interval);
                                 window.location.href = $element.attr('data-url');
                             }
                             else
                             {
                                 $('#' + id).html(--timer);
                             }
                         }, 1000);
                     },
        initAjax:    function (index, cb)
                     {
                         var parent = this;
                         $.ajax
                         ({
                             type:    'POST',
                             url:     parent.settings.route + '/' + parent.process[index],
                             data:    parent.post,
                             success: function (data)
                                      {
                                          var next = true;
                                          if(data.status !== 200)
                                          {
                                              next = false;
                                          }
                                          parent.post = data.post;
                                          parent.addLogs(data.logs);
                                          cb(next);
                                      }
                         });
                     },
        next:        function (index, next)
                     {
                         var parent = this;
                         if(next && typeof parent.process[index] !== 'undefined')
                         {
                             parent.initAjax(index, function (next)
                             {
                                 parent.percent(index);
                                 parent.next(index + 1, next);
                             });
                         }
                         else
                         {
                             if(next)
                             {
                                 parent.initEnd();
                             }
                         }
                     },
        addLogs:     function (logs)
                     {
                         var parent = this;
                         for(var i = 0; i < logs.length; i++)
                         {
                             $(parent.settings.container_logs).append("<div class='line'><span class='time'>" + logs[i][0] + "</span><span class='text " + logs[i][1] + "'>" + logs[i][2] + "</span></div>");
                         }
                     },
        percent:     function (index)
                     {
                         var parent = this;
                         var percent = Math.floor(index * 100 / parent.process.length);
                         $(parent.settings.container_start + ' .percent').addClass('p' + percent);
                         $(parent.settings.container_start + ' .percent span').html(percent + '%');
                     }
    };
    $.fn.install = function (options)
    {
        var settings = $.extend
        ({
            container:       '#install-container',
            container_start: "#install-start",
            container_end:   "#install-end",
            container_logs:  "#install-logs",
            route:           '/app_dev.php/install',
            process:         []
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));