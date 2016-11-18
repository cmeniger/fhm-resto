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
                      this.initForm();
                  },
        initForm: function ()
                  {
                      var parent = this;
                      $(parent.settings.container + ' form').unbind('submit').submit(function (e)
                      {
                          var $current = $(this);
                          var $submit  = $current.find(":submit");
                          $submit.attr('data-text', $submit.attr('value')).html($submit.attr('data-load')).prop('disabled', true);
                          e.preventDefault();
                          $.ajax
                          ({
                              type:    'POST',
                              url:     $current.attr('action'),
                              data:    $current.serialize(),
                              success: function (data)
                                       {
                                           $('[' + parent.settings.attribut + '=' + $current.attr('id') + ']').fadeToggle(400, "linear", function ()
                                           {
                                               $('[' + parent.settings.attribut + '=' + $current.attr('id') + ']').html(data).fadeToggle(400, "linear");
                                               $submit.html($submit.attr('data-text')).prop('disabled', false);
                                               parent.initForm();
                                           });
                                       }
                          });
                      });
                  }
    };
    $.fn.contact     = function (options)
    {
        var settings = $.extend
        ({
            container: '.contact-form-fields',
            attribut:  'data-id'
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));