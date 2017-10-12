(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.timers = [];
        this.init();
    }

    Plugin.prototype = {
        init:      function ()
                   {
                       var parent = this;
                       this.initTimer();
                   },
        initTimer: function ()
                   {
                       var parent = this;
                       $(parent.settings.container.timer).each(function (e)
                       {
                           var current = this;
                           var html = "";
                           var data = parent.getData(current);
                           var sectionDays = (typeof $(current).attr(parent.settings.attribut.days) !== 'undefined') ? $(current).attr(parent.settings.attribut.days) : parent.settings.section.days;
                           var sectionHours = (typeof $(current).attr(parent.settings.attribut.hours) !== 'undefined') ? $(current).attr(parent.settings.attribut.hours) : parent.settings.section.hours;
                           var sectionMinutes = (typeof $(current).attr(parent.settings.attribut.minutes) !== 'undefined') ? $(current).attr(parent.settings.attribut.minutes) : parent.settings.section.minutes;
                           var sectionSeconds = (typeof $(current).attr(parent.settings.attribut.seconds) !== 'undefined') ? $(current).attr(parent.settings.attribut.seconds) : parent.settings.section.seconds;
                           // HTML
                           html += (sectionDays == 'show' || (sectionDays == 'auto' && data.days > 0)) ? "<div class='section days'><span class='label'>" + parent.settings.translate.days + "</span><span class='data'>" + data.days + "</span></div>" : "";
                           html += (sectionHours == 'show') ? "<div class='section hours'><span class='label'>" + parent.settings.translate.hours + "</span><span class='data'>" + data.hours + "</span></div>" : "";
                           html += (sectionMinutes == 'show') ? "<div class='section minutes'><span class='label'>" + parent.settings.translate.minutes + "</span><span class='data'>" + data.minutes + "</span></div>" : "";
                           html += (sectionSeconds == 'show') ? "<div class='section seconds'><span class='label'>" + parent.settings.translate.seconds + "</span><span class='data'>" + data.seconds + "</span></div>" : "";
                           $(current).html(html);
                           // Timer
                           parent.timers.push(current);
                           setInterval(function ()
                           {
                               parent.refresh(current);
                           }, 1000);
                       });
                   },
        refresh:   function (timer)
                   {
                       var parent = this;
                       var data = parent.getData(timer);
                       $(timer).find(parent.settings.container.days).html(data.days);
                       $(timer).find(parent.settings.container.hours).html(data.hours);
                       $(timer).find(parent.settings.container.minutes).html(data.minutes);
                       $(timer).find(parent.settings.container.seconds).html(data.seconds);
                   },
        getData:   function (timer)
                   {
                       var parent = this;
                       var now = new Date().getTime();
                       var wait = parseInt($(timer).attr(parent.settings.attribut.timer)) - (now / 1000);
                       var days = Math.floor(wait / 86400);
                       var hours = Math.floor((wait - (days * 86400)) / 3600);
                       var minutes = Math.floor((wait - (days * 86400) - (hours * 3600)) / 60);
                       var seconds = Math.floor(wait - (days * 86400) - (hours * 3600) - (minutes * 60));
                       return {
                           wait:    wait,
                           days:    days,
                           hours:   hours,
                           minutes: minutes,
                           seconds: seconds
                       }
                   }
    };
    $.fn.timer = function (options)
    {
        var settings = $.extend
        ({
            container: {
                'timer':   '.timer-container',
                'days':    '.days .data',
                'hours':   '.hours .data',
                'minutes': '.minutes .data',
                'seconds': '.seconds .data'
            },
            attribut:  {
                'timer':   'data-timer',
                'days':    'data-days',
                'hours':   'data-hours',
                'minutes': 'data-minutes',
                'seconds': 'data-seconds',
            },
            section:   {
                'days':    'auto',
                'hours':   'show',
                'minutes': 'show',
                'seconds': 'hide'
            },
            translate: {
                'days':    'Days',
                'hours':   'Hours',
                'minutes': 'Minutes',
                'seconds': 'Seconds'
            }
        }, options);
        new Plugin(settings);
    };
}(jQuery, window, window.document));