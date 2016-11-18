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
                         this.initAdd();
                         this.initEdit();
                     },
        initAdd:     function ()
                     {
                         var parent = this;
                         $(parent.settings.add.note).each(function ()
                         {
                             parent.addInit($(this).attr('data-id'));
                             parent.addRefresh($(this).attr('data-id'), $(parent.settings.add.value + $(this).attr('data-id')).val());
                             $(this).mouseleave(function ()
                             {
                                 parent.addRefresh($(this).attr('data-id'), $(parent.settings.add.value + $(this).attr('data-id')).val());
                             });
                         });
                     },
        initEdit:    function ()
                     {
                         var parent = this;
                         $(parent.settings.edit.container).unbind('click').click(function (e)
                         {
                             $(parent.settings.edit.form).attr('action', $(this).attr('data-edit'));
                             $(parent.settings.edit.delete).attr('href', $(this).attr('data-delete'));
                             $(parent.settings.edit.value).val($(this).attr('data-value'));
                             $(parent.settings.edit.content).val($(this).attr('data-content'));
                             parent.editInit();
                             parent.editRefresh($(this).attr('data-value'));
                             $(parent.settings.edit.note).unbind('mouseleave').mouseleave(function ()
                             {
                                 parent.editRefresh($(parent.settings.edit.value).val());
                             });
                         });
                     },
        addInit:     function (id)
                     {
                         var parent = this;
                         $(parent.settings.add.note + ' ' + parent.settings.add.item + '[data-id=' + id + ']').mouseenter(
                             function ()
                             {
                                 parent.addRefresh(id, $(this).attr('data-value'));
                             }
                         );
                         $(parent.settings.add.note + ' ' + parent.settings.add.item + '[data-id=' + id + ']').click(
                             function ()
                             {
                                 $(parent.settings.add.value + id).val($(this).attr('data-value'));
                             }
                         );
                     },
        addRefresh:  function (id, value)
                     {
                         var parent = this;
                         if(value == 0)
                         {
                             $(parent.settings.add.legend + id).html('');
                         }
                         $(parent.settings.add.note + ' ' + parent.settings.add.item + '[data-id=' + id + ']').each(
                             function ()
                             {
                                 if($(this).attr('data-value') == value)
                                 {
                                     $(parent.settings.add.legend + id).html($(this).attr('data-legend'));
                                 }
                                 if($(this).attr('data-value') <= value)
                                 {
                                     $(this).removeClass(parent.settings.class.out).addClass(parent.settings.class.in);
                                 }
                                 else
                                 {
                                     $(this).removeClass(parent.settings.class.in).addClass(parent.settings.class.out);
                                 }
                             }
                         );
                     },
        editInit:    function ()
                     {
                         var parent = this;
                         $(parent.settings.edit.note + ' ' + parent.settings.edit.item).mouseenter(
                             function ()
                             {
                                 parent.editRefresh($(this).attr('data-value'));
                             }
                         );
                         $(parent.settings.edit.note + ' ' + parent.settings.edit.item).click(
                             function ()
                             {
                                 $(parent.settings.edit.value).val($(this).attr('data-value'));
                             }
                         );
                     },
        editRefresh: function (value)
                     {
                         var parent = this;
                         if(value == 0)
                         {
                             $(parent.settings.edit.legend).html('');
                         }
                         $(parent.settings.edit.note + ' ' + parent.settings.edit.item).each(
                             function ()
                             {
                                 if($(this).attr('data-value') == value)
                                 {
                                     $(parent.settings.edit.legend).html($(this).attr('data-legend'));
                                 }
                                 if($(this).attr('data-value') <= value)
                                 {
                                     $(this).removeClass(parent.settings.class.out).addClass(parent.settings.class.in);
                                 }
                                 else
                                 {
                                     $(this).removeClass(parent.settings.class.in).addClass(parent.settings.class.out);
                                 }
                             }
                         );
                     },
        getHtml:     function (data, selector)
                     {
                         var parent = this;
                         var $data = $($.parseHTML(data));
                         return $data.filter(selector).add($data.find(selector)).html();
                     },
        refresh:     function ()
                     {
                         var parent = this;
                         parent.initNote();
                         parent.initEdit();
                     }
    };
    $.fn.note = function (options)
    {
        var settings = $.extend
        ({
            container: '.note-user',
            add:       {
                note:    '.note-add-user',
                item:    '.note-add-user-item',
                legend:  '#note-add-legend-',
                value:   '#note-add-value-',
                content: '#note-add-content-',
                form:    '#note-add-form-'
            },
            edit:      {
                container: 'a[data-edit]',
                note:      '.note-edit-user',
                item:      '.note-edit-user-item',
                legend:    '#note-edit-legend',
                value:     '#note-edit-value',
                content:   '#note-edit-content',
                form:      '#note-edit-form',
                delete:    '#note-edit-delete'
            },
            modal:     {
                comment: '#note-comment-',
                add:     '#note-add-'
            },
            class:     {
                in:  'fa-star',
                out: 'fa-star-o'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));