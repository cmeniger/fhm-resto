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
                                                $(selector).fadeToggle(400, "linear", function ()
                                                {
                                                    $(selector).html(parent.getHtml(data, selector)).fadeToggle(400, "linear");
                                                    $submit.html($submit.attr('data-text')).prop('disabled', false);
                                                    parent.refreshScript(data);
                                                    parent.initAjax();
                                                });
                                            }
                               });
                           });
                       },
        refreshScript: function (data)
                       {
                           var parent = this;
                           $('<div>' + data + '</div>').find('script').each(function ()
                           {
                               eval(this.innerHTML);
                           });
                           if(typeof grecaptcha !== "undefined")
                           {
                               grecaptcha.reset();
                           }
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
                form:    'form[data-type=ajax]',
                content: 'data-content',
                load:    '<i class="fa fa-circle-o-notch fa-spin"></i>'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));