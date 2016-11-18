/**
 * plugin.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2015 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*jshint unused:false */
/*global tinymce:true */
/**
 * Example plugin that adds a toolbar button and menu item.
 */
tinymce.PluginManager.requireLangPack('fhmmedia');
tinymce.PluginManager.add('fhmmedia', function (editor, url)
{
    var settings   = editor.settings.fhmmedia ? editor.settings.fhmmedia : null;
    var tag        = null;
    var search     = null;
    var pagination = 1;

    /**
     * Add event
     */
    function addEvent()
    {
        $('a[data-type]').unbind('click').click(function ()
        {
            event.preventDefault();
            var type = $(this).attr('data-type');
            if(type == 'link')
            {
                insertLink(this);
            }
            if(type == 'image')
            {
                insertImage(this);
            }
            if(type == 'div')
            {
                insertDiv(this);
            }
        });
    }

    /**
     * Insert link
     */
    function insertLink(elem)
    {
        var html = "<a href='" + $(elem).attr('data-url') + "' title='" + $(elem).attr('data-title') + "' target='_blank'>" + $(elem).attr('data-text') + "</a>";
        insertContent(html);
    }

    /**
     * Insert image
     */
    function insertImage(elem)
    {
        var html = "<img src='" + $(elem).attr('data-url') + "' title='" + $(elem).attr('data-title') + "' alt='" + $(elem).attr('data-title') + "' width='" + $(elem).attr('data-width') + "' height='" + $(elem).attr('data-height') + "'/>";
        insertContent(html);
    }

    /**
     * Insert div
     */
    function insertDiv(elem)
    {
        var html = $(elem).html();
        insertContent(html);
    }

    /**
     * Insert content
     */
    function insertContent(html)
    {
        editor.insertContent(html);
        closeDialog();
    }

    /**
     * Close dialog
     */
    function closeDialog()
    {
        editor.windowManager.close();
    }

    /**
     * Init
     */
    function init()
    {
        // Search
        $('input[data-type=search]').closest('form').unbind('submit').submit(function (e)
        {
            search     = $(this).find('.FhmSearch_search').val();
            pagination = 1;
            refresh();
            return false;
        });
        // Reset
        $('button[type=reset]').unbind('click').click(function (e)
        {
            search     = null;
            tag        = null;
            pagination = 1;
            refresh();
            return false;
        });
        // Tags
        $('a[media-tag]').unbind('click').click(function (e)
        {
            tag        = $(this).attr('media-tag');
            pagination = 1;
            refresh();
            return false;
        });
        // Pagination
        $('a[media-pagination]').unbind('click').click(function (e)
        {
            pagination = $(this).attr('media-pagination');
            refresh();
            return false;
        });
        // New
        $('a[media-new]').unbind('click').click(function (e)
        {
            $.ajax
            ({
                type:    'POST',
                url:     settings.url_new ? settings.url_new : '/api/media/data/editor/new',
                data:    {
                    selector: {
                        filter:  settings.filter ? settings.filter : '',
                        root:    settings.root ? settings.root : '',
                        private: settings.private ? settings.private : false,
                        id:      editor.id
                    }
                },
                success: function (data)
                         {
                             $('#mce-fhmmedia-panel-' + editor.id).fadeToggle(400, "linear", function ()
                             {
                                 $('#mce-fhmmedia-panel-' + editor.id).html(data).fadeToggle(400, "linear");
                                 init();
                             });
                         }
            });
            return false;
        });
        // Selector
        $('a[media-selector]').unbind('click').click(function (e)
        {
            refresh();
            return false;
        });
    }

    /**
     * Refresh
     */
    function refresh()
    {
        $.ajax
        ({
            type:    'POST',
            url:     settings.url_selector ? settings.url_selector : '/api/media/data/editor',
            data:    {
                media:    {
                    tag:        tag,
                    search:     search,
                    pagination: pagination
                },
                selector: {
                    filter:  settings.filter ? settings.filter : '',
                    root:    settings.root ? settings.root : '',
                    private: settings.private ? settings.private : false,
                    id:      editor.id
                }
            },
            success: function (data)
                     {
                         $('#mce-fhmmedia-panel-' + editor.id).fadeToggle(400, "linear", function ()
                         {
                             $('#mce-fhmmedia-panel-' + editor.id).html(data).fadeToggle(400, "linear");
                             addEvent();
                             init();
                         });
                     }
        });
    }

    /**
     * Dialog
     */
    function showDialog()
    {
        var mediaHtml = "<div id='mce-fhmmedia-panel-" + editor.id + "'><div class='mce-fhmmedia-load'><i class='fa fa-refresh fa-spin'></i></div></div>";
        editor.windowManager.open({
            autoScroll: true,
            width:      900,
            height:     700,
            title:      'Insert media',
            html:       mediaHtml,
            buttons:    [
                {
                    text:    'Close',
                    onclick: 'close'
                }
            ]
        });
        refresh();
    }

    /**
     * Init
     */
    editor.on('init', function ()
    {
        var csslink = editor.dom.create('link', {
            rel:  'stylesheet',
            href: url + '/css/fhmmedia.css'
        });
        document.getElementsByTagName('head')[0].appendChild(csslink);
    });
    /**
     * Toolbar button
     */
    editor.addButton('fhmmedia', {
        icon:    'fhmfile',
        tooltip: 'Insert media',
        onclick: showDialog
    });
    /**
     * Toolbar menu
     */
    editor.addMenuItem('fhmmedia', {
        text:             'Insert media',
        icon:             'fhmfile',
        context:          'insert',
        prependToContext: true,
        onclick:          showDialog
    });
    /**
     * Command new
     */
    editor.addCommand('fhmmediaNew', function (ui, value)
    {
        search = value;
        refresh();
    });
});