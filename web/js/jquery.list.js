(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.search = '';
        this.pagination = 1;
        this.sort = {field: '', order: ''};
        this.current = {};
        this.init();
    }

    Plugin.prototype = {
        init:           function ()
                        {
                            var parent = this;
                            this.initCurrent();
                            this.initSearch();
                            this.initPagination();
                            this.initSort();
                        },
        initCurrent:    function ()
                        {
                            var parent = this;
                            parent.current = {
                                path:       this.settings.url,
                                data:       this.settings.container.data,
                                pagination: this.settings.container.pagination
                            };
                        },
        initSearch:     function ()
                        {
                            var parent = this;
                            $(parent.settings.search.container).closest('form').unbind('submit').submit(function (e)
                            {
                                e.preventDefault();
                                parent.search = $(parent.settings.search.container).val();
                                parent.pagination = 1;
                                parent.refresh();
                            });
                        },
        initPagination: function ()
                        {
                            var parent = this;
                            $(parent.settings.pagination.container).unbind('click').click(function (e)
                            {
                                if($(this).attr("data-active") == 0)
                                {
                                    return false;
                                }
                                $("li.pagination-loader").show("fast");
                                parent.current = {
                                    path:       $(this).attr('data-path'),
                                    data:       '#' + $(this).attr('data-id-data'),
                                    pagination: '#' + $(this).attr('data-id-pagination')
                                };
                                parent.pagination = $(this).attr('data-pagination');
                                parent.refresh();
                            });
                        },
        initSort:       function ()
                        {
                            var parent = this;
                            $(parent.settings.sort.container).unbind('click').click(function (e)
                            {
                                e.preventDefault();
                                parent.sort.field = $(this).attr("data-field");
                                parent.sort.order = $(this).attr("data-order");
                                parent.pagination = 1;
                                parent.refresh();
                            });
                        },
        initExtern:       function ()
                        {
                            var parent = this;
                            $(document).historic();
                        },
        refresh:        function (index)
                        {
                            var parent = this;
                            $.ajax
                            ({
                                type:    'POST',
                                url:     parent.current.path,
                                data:    {
                                    FhmSearch:     {
                                        search: parent.search
                                    },
                                    FhmPagination: {
                                        pagination: parent.pagination
                                    },
                                    FhmSort:       {
                                        field: parent.sort.field,
                                        order: parent.sort.order
                                    }
                                },
                                success: function (data)
                                         {
                                             $(parent.current.data).fadeToggle(400, "linear", function ()
                                             {
                                                 $(parent.current.data).html(parent.getHtml(data, parent.current.data)).fadeToggle(400, "linear");
                                                 $(parent.current.pagination).html(parent.getHtml(data, parent.current.pagination));
                                                 parent.initCurrent();
                                                 parent.initSearch();
                                                 parent.initPagination();
                                                 parent.initSort();
                                                 parent.initExtern();
                                             });
                                         }
                            });
                        },
        getHtml:        function (data, selector)
                        {
                            var parent = this;
                            var $data = $($.parseHTML(data));
                            return $data.filter(selector).add($data.find(selector)).html();
                        }
    };
    $.fn.list = function (options)
    {
        var settings = $.extend
        ({
            url:        $('#FhmSearch_search').closest('form').attr('action'),
            container:  {
                data:       '#content_data',
                pagination: '#content_pagination'
            },
            search:     {
                container: 'input[data-type=list]'
            },
            pagination: {
                container: 'a[data-pagination]',
                tag:       'pagination'
            },
            sort:       {
                container: 'a[data-sort]'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));