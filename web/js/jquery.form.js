(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.init();
    }

    Plugin.prototype = {
        init:     function ()
                  {
                      var parent = this;
                      this.initAjax();
                  },
        initAjax: function ()
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
                                               parent.initAjax();
                                           });
                                       }
                          });
                      });
                  },
        getHtml:  function (data, selector)
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
            ajax: {
                form:    'form[data-type=ajax]',
                content: 'data-content',
                load:    '<i class="fa fa-circle-o-notch fa-spin"></i>'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));