(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.init();
    }

    Plugin.prototype = {
        init:          function ()
                       {
                           var parent = this;
                           this.initForm();
                           this.initAjax();
                       },
        initForm:      function ()
                       {
                           var parent = this;
                           $('form').each(function (e)
                           {
                               if((typeof $(this).attr('data-required') == 'undefined' || $(this).attr('data-required') == true) && $(this).find('.form-required').length == 0)
                               {
                                   $(this).append("<div class='form-required'>" + parent.settings.required + "</div>");
                               }
                           });
                           if(parent.settings.form.select.multi_click)
                           {
                               $('form select option').unbind('mousedown').mousedown(function (e)
                               {
                                   e.preventDefault();
                                   $(this).prop('selected', $(this).prop('selected') ? false : true);
                                   $(this).parent().change();
                                   return false;
                               });
                           }
                       },
        initAjax:      function ()
                       {
                           var parent = this;
                           $(parent.settings.ajax.form).unbind('submit').submit(function (e)
                           {
                               var $current = $(this);
                               var $submit = $current.find(":submit");
                               var load = typeof $submit.attr('data-load') == 'undefined' ? parent.settings.ajax.load : $submit.attr('data-load');
                               var selector = $current.attr(parent.settings.ajax.content);
                               $submit.attr('data-text', $submit.attr('value')).html(load).prop('disabled', true);
                               e.preventDefault();
                               $.ajax
                               ({
                                   type:    'POST',
                                   url:     $current.attr('action'),
                                   data:    $current.serialize(),
                                   success: function (data)
                                            {
                                                if(typeof $current.attr(parent.settings.ajax.animation) !== 'undefined' && $current.attr(parent.settings.ajax.animation) === true)
                                                {
                                                    $(selector).fadeToggle(400, "linear", function ()
                                                    {
                                                        $(selector).html(parent.getHtml(data, selector)).fadeToggle(400, "linear");
                                                        $submit.html($submit.attr('data-text')).prop('disabled', false);
                                                        parent.refreshScript(data);
                                                        parent.initAjax();
                                                        parent.initAlert($current);
                                                    });
                                                }
                                                else
                                                {
                                                    $(selector).html(parent.getHtml(data, selector));
                                                    $submit.html($submit.attr('data-text')).prop('disabled', false);
                                                    parent.refreshScript(data);
                                                    parent.initAjax();
                                                    parent.initAlert($current);
                                                }
                                            }
                               });
                           });
                           $(parent.settings.ajax.form).each(function ()
                           {
                               var $current = $(this);
                               if(typeof $current.attr(parent.settings.ajax.live) !== 'undefined')
                               {
                                   $current.find(":input").each(function ()
                                   {
                                       $(this).unbind('change').change(function ()
                                       {
                                           $current.trigger('submit');
                                       });
                                   });
                               }
                           });
                           $(parent.settings.ajax.link).unbind('click').click(function (e)
                           {
                               var $current = $(this);
                               var selector = $current.attr(parent.settings.ajax.content);
                               e.preventDefault();
                               $.ajax
                               ({
                                   type:    'POST',
                                   url:     $current.attr('href'),
                                   data:    {},
                                   success: function (data)
                                            {
                                                $(selector).fadeToggle(400, "linear", function ()
                                                {
                                                    $(selector).html(parent.getHtml(data, selector)).fadeToggle(400, "linear");
                                                    parent.refreshScript(data);
                                                    parent.initAjax();
                                                    parent.initAlert($current);
                                                });
                                            }
                               });
                           });
                       },
        initAlert:     function ($current)
                       {
                           var parent = this;
                           setTimeout(function ()
                           {
                               $current.find(parent.settings.ajax.alert).fadeOut();
                           }, 5000);
                       },
        refreshScript: function (data)
                       {
                           var parent = this;
                           $('<div>' + data + '</div>').find('script').each(function ()
                           {
                               eval(this.innerHTML);
                           });
                           $('.g-recaptcha').each(function ()
                           {
                               if(typeof grecaptcha !== "undefined")
                               {
                                   grecaptcha.render(this, {
                                       'sitekey': $(this).attr("data-sitekey")
                                   });
                               }
                           });
                       },
        getHtml:       function (data, selector)
                       {
                           var parent = this;
                           var $data = $($.parseHTML(data));
                           return $data.filter(selector).add($data.find(selector)).html();
                       }
    };
    $.fn.form = function (options)
    {
        var settings = $.extend
        ({
            required: '',
            form:     {
                tag:    'data-required',
                select: {
                    multi_click: true
                }
            },
            ajax:     {
                form:      'form[data-type=ajax]',
                link:      'a[data-type=ajax]',
                content:   'data-content',
                live:      'data-live',
                animation: 'data-animation',
                alert:     '.alert-box',
                load:      '<i class="fa fa-circle-o-notch fa-spin"></i>'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));