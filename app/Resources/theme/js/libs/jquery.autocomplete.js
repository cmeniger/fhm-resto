(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.init();
    }

    Plugin.prototype = {
        init:           function ()
        {
            var parent = this;
            this.initInput();
            this.initButton();
            this.initList();
            this.initForm();
        },
        initInput:      function ()
        {
            var parent = this;
            $(parent.settings.input.container).keyup(function (e)
            {
                var current = this;
                var text = $(current).val();
                // Reset hidden field
                $('#' + $(current).attr('data-field')).val('');
                // Refresh search
                if(text != "")
                {
                    $('#' + $(current).attr('data-field') + parent.settings.list.suffix).html(parent.settings.list.content).fadeIn(400);
                    $.ajax
                    ({
                        type:    'POST',
                        url:     $(current).attr('data-url'),
                        data:    {
                            text:       text,
                            pagination: 1,
                            field:      $(current).attr('data-field')
                        },
                        success: function (data)
                        {
                            $('#' + $(current).attr('data-field') + parent.settings.list.suffix).html(data);
                            parent.initElement();
                            parent.initPagination();
                        }
                    });
                }
                // Reset field
                else
                {
                    $('#' + $(current).attr('data-field') + parent.settings.list.suffix).html(parent.settings.list.content).fadeOut(400);
                }
                // Add focus out event
                $(current).unbind('focusout').focusout(function ()
                {
                    if($('#' + $(this).attr('data-field')).val() == '')
                    {
                        $(this).val('');
                        $('#' + $(this).attr('data-field') + parent.settings.list.suffix).fadeOut(400);
                    }
                });
            });
        },
        initButton:     function ()
        {
            var parent = this;
            $(parent.settings.button.container).click(function (e)
            {
                e.preventDefault();
                $('#' + $(this).attr('data-field') + parent.settings.list.suffix).fadeToggle(400, "linear");
            });
        },
        initList:       function ()
        {
            var parent = this;
            $(parent.settings.list.container).each(function (e)
            {
                $(this).html(parent.settings.list.content);
            });
        },
        initElement:    function ()
        {
            var parent = this;
            $(parent.settings.element.container).click(function (e)
            {
                e.preventDefault();
                $('#' + $(this).attr('data-field')).val($(this).attr('data-id')).trigger('change');
                $('#' + $(this).attr('data-field') + parent.settings.input.suffix).val($(this).attr('data-name')).trigger('change');
                $('#' + $(this).attr('data-field') + parent.settings.list.suffix).fadeOut(400);
            });
        },
        initPagination: function ()
        {
            var parent = this;
            $(parent.settings.pagination.container).click(function (e)
            {
                e.preventDefault();
                var current = this;
                $(parent.settings.pagination.loader).show();
                $.ajax
                ({
                    type:    'POST',
                    url:     $(current).attr('data-url'),
                    data:    {
                        text:       $(current).attr('data-text'),
                        pagination: $(current).attr('data-pagination'),
                        field:      $(current).attr('data-field')
                    },
                    success: function (data)
                    {
                        $('#' + $(current).attr('data-field') + parent.settings.list.suffix).html(data).show();
                        parent.initElement();
                        parent.initPagination();
                    }
                });
            });
        },
        initForm:       function ()
        {
            var parent = this;
            var error = false;
            $('form').submit(function (e)
            {
                $(parent.settings.input.container).each(function ()
                {
                    if(typeof $(this).attr('required') != 'undefined' && $('#' + $(this).attr('data-field')).val() == '')
                    {
                        $(this).val('');
                        $('#' + $(this).attr('data-field') + parent.settings.list.suffix).fadeOut(400);
                        error = true;
                    }
                });
                if(error)
                {
                    return false;
                }
            });
        }
    };
    $.fn.autocomplete = function (options)
    {
        var settings = $.extend
        ({
            button:     {
                container: '.autocomplete a[data-autocomplete]',
                suffix:    '-button'
            },
            input:      {
                container: '.autocomplete input[data-autocomplete]',
                suffix:    '-input'
            },
            list:       {
                container: '.autocomplete .list',
                content:   '<div class="refresh"><i class="fa fa-refresh fa-spin"></i></div>',
                suffix:    '-list'
            },
            element:    {
                container: '.autocomplete a.element'
            },
            pagination: {
                container: '.autocomplete .pagination a',
                loader:    '.pagination-loader'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));