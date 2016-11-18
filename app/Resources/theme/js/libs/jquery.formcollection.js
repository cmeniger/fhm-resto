(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.init();
    }

    Plugin.prototype = {
        init:       function ()
        {
            var parent = this;
            this.master = $(this.settings.container)
            this.linkAdd = $('<a href="#" class="' + this.settings.add.class + '">' + this.settings.add.text + '</a>');
            this.linkRemove = $('<a href="#" class="' + this.settings.remove.class + '">' + this.settings.remove.text + '</a>');
            this.linkAdd.on('click', function (e)
            {
                e.preventDefault();
                parent.addForm();
            });
            this.master.after(this.linkAdd);
            this.master.find('li').each(function ()
            {
                parent.removeLink(this);
            });
        },
        addForm:    function ()
        {
            var parent = this;
            var prototype = this.master.attr('data-prototype');
            var obj = $(prototype.replace(/__name__/g, this.master.children().length));
            parent.master.append($('<li></li>').append(obj));
            parent.removeLink(obj);
        },
        removeLink: function (obj)
        {
            var parent = this;
            var link = parent.linkRemove.clone();
            link.on('click', function (e)
            {
                e.preventDefault();
                parent.removeForm(obj.parent());
            });
            obj.append(link);
        },
        removeForm: function (obj)
        {
            var parent = this;
            obj.remove();
        }
    };
    $.fn.formcollection = function (options)
    {
        var settings = $.extend
        ({
            container: 'ul.form_collection',
            add:       {
                class: 'button tiny form_collection_add',
                text:  '<i class="fa fa-plus"></i>'
            },
            remove:    {
                class: 'button tiny form_collection_remove',
                text:  '<i class="fa fa-minus"></i>'
            }
        }, options);
        new Plugin(settings);
    };
}(jQuery, window, window.document));