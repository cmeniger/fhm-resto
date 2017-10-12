(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.tag = '';
        this.search = '';
        this.pagination = 1;
        this.selectors = [];
        this.cache = [];
        this.init();
    }

    Plugin.prototype = {
        init:            function ()
                         {
                             var parent = this;
                             this.initTags();
                             this.initSearch();
                             this.initPagination();
                             this.initSelector();
                             this.initZoom();
                         },
        initTags:        function ()
                         {
                             var parent = this;
                             $(parent.settings.tag.container).unbind('click').click(function (e)
                             {
                                 e.preventDefault();
                                 parent.tag = $(this).attr(parent.settings.tag.attribute);
                                 parent.pagination = 1;
                                 parent.refresh($(this).attr('data-selector'));
                             });
                         },
        initSearch:      function ()
                         {
                             var parent = this;
                             $(parent.settings.search.container).closest('form').unbind('submit').submit(function (e)
                             {
                                 e.preventDefault();
                                 parent.search = $(this).find(parent.settings.search.field).val();
                                 parent.pagination = 1;
                                 parent.refresh($(this).attr('data-selector'));
                             });
                         },
        initPagination:  function ()
                         {
                             var parent = this;
                             $("a[" + parent.settings.pagination.attribute + "]").unbind('click').click(function (e)
                             {
                                 if($(this).attr("data-active") == 0)
                                 {
                                     return false;
                                 }
                                 $("li.pagination-loader").show("fast");
                                 parent.pagination = $(this).attr(parent.settings.pagination.attribute);
                                 parent.refresh($(this).attr('data-selector'));
                             });
                         },
        initSelector:    function ()
                         {
                             var parent = this;
                             var index = 0;
                             $(parent.settings.selector.container).each(function ()
                             {
                                 // Cache
                                 parent.cache[$(this).attr('data-id')] = {
                                     id:      $(this).attr('data-id'),
                                     private: $(this).attr('data-private'),
                                     root:    $(this).attr('data-root'),
                                     filter:  $(this).attr('data-filter')
                                 };
                                 // Disabled
                                 if($(this).hasClass('disabled'))
                                 {
                                     $(this).unbind('click').click(function (e)
                                     {
                                         e.preventDefault();
                                         return false;
                                     });
                                 }
                                 else
                                 {
                                     if($('#' + $(this).attr('data-id')).val() != '')
                                     {
                                         var current = this;
                                         $.ajax
                                         ({
                                             type:    'POST',
                                             url:     parent.settings.clickable.initialization,
                                             data:    {
                                                 id: $('#' + $(this).attr('data-id')).val()
                                             },
                                             success: function (data)
                                                      {
                                                          $(current).find(parent.settings.selector.default).hide();
                                                          $(current).find(parent.settings.selector.preview).html(data).show();
                                                          $(parent.settings.reset.container + '[data-id=' + $(current).attr('data-id') + ']').show();
                                                          parent.initReset();
                                                          parent.initTarget(current);
                                                      }
                                         });
                                     }
                                     $(this).attr('data-selector', index).unbind('click').click(function (e)
                                     {
                                         var current = this;
                                         $.ajax
                                         ({
                                             type:    'POST',
                                             url:     parent.settings.selector.initialization,
                                             data:    {
                                                 selector: {
                                                     index:    $(current).attr('data-selector'),
                                                     filter:   parent.cache[$(this).attr('data-id')].filter,
                                                     root:     parent.cache[$(this).attr('data-id')].root,
                                                     private:  parent.cache[$(this).attr('data-id')].private,
                                                     selected: $('#' + $(this).attr('data-id')).val(),
                                                     new:      $(this).attr('data-reveal-id') + '-new'
                                                 }
                                             },
                                             success: function (data)
                                                      {
                                                          $('#' + $(current).attr('data-reveal-id')).html(data);
                                                          parent.initTags();
                                                          parent.initPagination();
                                                          parent.initSearch();
                                                          parent.initClickable();
                                                          parent.initReset();
                                                          parent.initNew();
                                                          parent.initTarget(current);
                                                          parent.refreshSelector($(current).attr('data-selector'));
                                                      }
                                         });
                                     });
                                     parent.selectors.push(this);
                                     index = index + 1;
                                 }
                             });
                         },
        initClickable:   function ()
                         {
                             var parent = this;
                             $(parent.settings.clickable.container).unbind('click').click(function (e)
                             {
                                 $('#' + $(parent.selectors[$(this).attr('data-selector')]).attr('data-id') + '-field').val($(this).attr('media-id'));
                                 $('#' + $(parent.selectors[$(this).attr('data-selector')]).attr('data-id')).val($(this).attr('media-id')).trigger('change');
                             });
                         },
        initZoom:        function ()
                         {
                             var parent = this;
                             $(parent.settings.zoom.container).unbind('click').click(function (e)
                             {
                                 e.preventDefault();
                                 var current = this;
                                 $.ajax
                                 ({
                                     type:    'POST',
                                     url:     parent.settings.zoom.initialization,
                                     data:    {
                                         id: $(current).attr('media-id')
                                     },
                                     success: function (data)
                                              {
                                                  $('#' + $(current).attr("data-reveal-id")).find("." + parent.settings.zoom.attribute).html(data);
                                              }
                                 });
                             });
                         },
        initReset:       function ()
                         {
                             var parent = this;
                             $(parent.settings.reset.container).unbind('click').click(function (e)
                             {
                                 e.preventDefault();
                                 $('#' + $(this).attr('data-id') + '-field').val('');
                                 $('#' + $(this).attr('data-id')).val('').trigger('change');
                             });
                         },
        initNew:         function ()
                         {
                             var parent = this;
                             $(parent.settings.new.container).unbind('click').click(function (e)
                             {
                                 var current = this;
                                 $.ajax
                                 ({
                                     type:    'POST',
                                     url:     parent.settings.new.initialization,
                                     data:    {
                                         selector: {
                                             index:    $(current).attr('data-selector'),
                                             selector: $(parent.selectors[$(current).attr('data-selector')]).attr('data-reveal-id'),
                                             target:   parent.cache[$(parent.selectors[$(current).attr('data-selector')]).attr('data-id')].id,
                                             filter:   parent.cache[$(parent.selectors[$(current).attr('data-selector')]).attr('data-id')].filter,
                                             root:     parent.cache[$(parent.selectors[$(current).attr('data-selector')]).attr('data-id')].root,
                                             private:  parent.cache[$(parent.selectors[$(current).attr('data-selector')]).attr('data-id')].private
                                         }
                                     },
                                     success: function (data)
                                              {
                                                  $('#' + $(parent.selectors[$(current).attr('data-selector')]).attr('data-reveal-id') + '-new').html(data);
                                              }
                                 });
                             });
                         },
        initTarget:      function (current)
                         {
                             var parent = this;
                             $('#' + $(current).attr('data-id')).change(function ()
                             {
                                 var id = $(this).val();
                                 if(id == '')
                                 {
                                     $(current).find(parent.settings.selector.default).show();
                                     $(current).find(parent.settings.selector.preview).hide();
                                     $(parent.settings.reset.container + '[data-id=' + $(current).attr('data-id') + ']').hide();
                                 }
                                 else
                                 {
                                     $.ajax
                                     ({
                                         type:    'POST',
                                         url:     parent.settings.clickable.initialization,
                                         data:    {
                                             id: id
                                         },
                                         success: function (data)
                                                  {
                                                      $(current).find(parent.settings.selector.default).hide();
                                                      $(current).find(parent.settings.selector.preview).html(data).show();
                                                      $(parent.settings.reset.container + '[data-id=' + $(current).attr('data-id') + ']').show();
                                                  }
                                     });
                                 }
                                 $('#' + $(parent.selectors[$(current).attr('data-selector')]).attr('data-reveal-id')).foundation('reveal', 'close');
                             });
                         },
        refresh:         function (index)
                         {
                             var parent = this;
                             $.ajax
                             ({
                                 type:    'POST',
                                 url:     $(parent.settings.container).attr('data-path'),
                                 data:    {
                                     media:    {
                                         tag:        this.tag,
                                         search:     this.search,
                                         pagination: this.pagination
                                     },
                                     selector: {
                                         filter:   (typeof index != "undefined") ? parent.cache[$(parent.selectors[index]).attr('data-id')].filter : null,
                                         root:     (typeof index != "undefined") ? parent.cache[$(parent.selectors[index]).attr('data-id')].root : null,
                                         private:  (typeof index != "undefined") ? parent.cache[$(parent.selectors[index]).attr('data-id')].private : null,
                                         selected: (typeof index != "undefined") ? $('#' + $(parent.selectors[index]).attr('data-id')).val() : null,
                                         new:      (typeof index != "undefined") ? $(parent.selectors[index]).attr('data-reveal-id') + '-new' : null
                                     }
                                 },
                                 success: function (data)
                                          {
                                              if(typeof index != "undefined")
                                              {
                                                  var modal = $('#' + $(parent.selectors[index]).attr('data-reveal-id'));
                                                  modal.find(parent.settings.container).fadeToggle(400, "linear", function ()
                                                  {
                                                      modal.find(parent.settings.container).html(parent.getHtml(data, parent.settings.container)).fadeToggle(400, "linear");
                                                      parent.initTags();
                                                      parent.initPagination();
                                                      parent.initClickable();
                                                      parent.initNew();
                                                      parent.initZoom();
                                                      parent.refreshSelector(index);
                                                  });
                                              }
                                              else
                                              {
                                                  $(parent.settings.container).fadeToggle(400, "linear", function ()
                                                  {
                                                      $(parent.settings.container).html(parent.getHtml(data, parent.settings.container)).fadeToggle(400, "linear");
                                                      parent.initTags();
                                                      parent.initPagination();
                                                      parent.initClickable();
                                                      parent.initNew();
                                                      parent.initZoom();
                                                  });
                                              }
                                          }
                             });
                         },
        refreshSelector: function (index)
                         {
                             var parent = this;
                             var modal = $('#' + $(parent.selectors[index]).attr('data-reveal-id'));
                             modal.find(parent.settings.pagination.container).attr('data-selector', index);
                             modal.find(parent.settings.tag.container).attr('data-selector', index);
                             modal.find(parent.settings.search.container).closest('form').attr('data-selector', index);
                             modal.find(parent.settings.clickable.container).attr('data-selector', index);
                             modal.find(parent.settings.new.container).attr('data-selector', index);
                             $(parent.settings.reset.container + '[data-id=' + $(parent.selectors[index]).attr('data-id') + ']').attr('data-selector', index);
                         },
        getHtml:         function (data, selector)
                         {
                             var parent = this;
                             var $data = $($.parseHTML(data));
                             return $data.filter(selector).add($data.find(selector)).html();
                         }
    };
    $.fn.media = function (options)
    {
        var settings = $.extend
        ({
            container:  '.media',
            tag:        {
                container: 'a[media-tag]',
                attribute: 'media-tag'
            },
            search:     {
                container: 'input[data-type=search]',
                field:     '.FhmSearch_search'
            },
            pagination: {
                container: 'a[media-pagination]',
                attribute: 'media-pagination'
            },
            clickable:  {
                container:      '.data.clickable',
                initialization: '/api/media/data/preview'
            },
            zoom:       {
                container:      'a[media-zoom]',
                attribute:      'media-zoom',
                initialization: '/api/media/data/zoom'
            },
            reset:      {
                container: '.media-reset'
            },
            selector:   {
                container:      '.media-selector',
                initialization: '/api/media/data/selector',
                default:        '.default',
                preview:        '.preview'
            },
            new:        {
                container:      '.media-new',
                modal:          'modal-media-new',
                initialization: '/api/media/data/new'
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));