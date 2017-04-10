(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.init();
    }

    Plugin.prototype = {
        init:                function ()
                             {
                                 var parent = this;
                                 this.initTabs();
                                 this.initSearch();
                                 this.initModalAjax();
                                 this.initModalCategory();
                                 this.initModalProduct();
                                 this.initModalIngredient();
                             },
        initTabs:            function ()
                             {
                                 var parent = this;
                                 $(parent.settings.tabs.container).unbind('click').click(function (e)
                                 {
                                     e.preventDefault();
                                     $(parent.settings.tabs.container).removeClass('active');
                                     $(this).addClass('active');
                                     $.ajax
                                     ({
                                         type:    'POST',
                                         url:     $(this).attr('data-url'),
                                         data:    {},
                                         success: function (data)
                                                  {
                                                      $(parent.settings.tabs.content).html(data);
                                                      parent.refresh();
                                                  }
                                     });
                                 });
                             },
        initSearch:          function ()
                             {
                                 var parent = this;
                                 $(parent.settings.search.container).unbind('keyup').keyup(function (e)
                                 {
                                     $.ajax
                                     ({
                                         type:    'POST',
                                         url:     $(this).attr('data-url'),
                                         data:    {
                                             search: $(this).val()
                                         },
                                         success: function (data)
                                                  {
                                                      $(parent.settings.search.content).html(data);
                                                  }
                                     });
                                 });
                             },
        initModalAjax:       function ()
                             {
                                 var parent = this;
                                 $(parent.settings.modal.ajax).unbind('click').click(function (e)
                                 {
                                     e.preventDefault();
                                     if($(this).attr('data-confirm') == 'undefined' || confirm($(this).attr('data-confirm')))
                                     {
                                         $.ajax
                                         ({
                                             type:    'POST',
                                             url:     $(this).attr('href'),
                                             data:    {},
                                             success: function (data)
                                                      {
                                                          $(parent.settings.tabs.content).html(data.html);
                                                          parent.refresh();
                                                      }
                                         });
                                     }
                                 });
                             },
        initModalCategory:   function ()
                             {
                                 var parent = this;
                                 $(parent.settings.modal.category.container).unbind('click').click(function (e)
                                 {
                                     $.ajax
                                     ({
                                         type:    'POST',
                                         url:     $(this).attr('data-url'),
                                         data:    {},
                                         success: function (data)
                                                  {
                                                      $(parent.settings.modal.category.content).html(data);
                                                      $(parent.settings.modal.category.form).unbind('submit').submit(function (e)
                                                      {
                                                          e.preventDefault();
                                                          $.ajax
                                                          ({
                                                              type:    'POST',
                                                              url:     $(this).attr('action'),
                                                              data:    $(this).serialize(),
                                                              success: function (data)
                                                                       {
                                                                           if(typeof data.status == 'undefined')
                                                                           {
                                                                               $(parent.settings.modal.category.content).html(data);
                                                                           }
                                                                           else
                                                                           {
                                                                               $(parent.settings.modal.category.id).foundation('reveal', 'close');
                                                                               $(parent.settings.tabs.content).html(data.html);
                                                                           }
                                                                           parent.refresh();
                                                                       }
                                                          });
                                                      });
                                                      parent.refresh();
                                                  }
                                     });
                                 });
                             },
        initModalProduct:    function ()
                             {
                                 var parent = this;
                                 $(parent.settings.modal.product.container).unbind('click').click(function (e)
                                 {
                                     $.ajax
                                     ({
                                         type:    'POST',
                                         url:     $(this).attr('data-url'),
                                         data:    {},
                                         success: function (data)
                                                  {
                                                      $(parent.settings.modal.product.content).html(data);
                                                      $(parent.settings.modal.product.form).unbind('submit').submit(function (e)
                                                      {
                                                          e.preventDefault();
                                                          $.ajax
                                                          ({
                                                              type:    'POST',
                                                              url:     $(this).attr('action'),
                                                              data:    $(this).serialize(),
                                                              success: function (data)
                                                                       {
                                                                           if(typeof data.status == 'undefined')
                                                                           {
                                                                               $(parent.settings.modal.product.content).html(data);
                                                                           }
                                                                           else
                                                                           {
                                                                               $(parent.settings.modal.product.id).foundation('reveal', 'close');
                                                                               $(parent.settings.tabs.content).html(data.html);
                                                                           }
                                                                           parent.refresh();
                                                                       }
                                                          });
                                                      });
                                                      parent.refresh();
                                                  }
                                     });
                                 });
                             },
        initModalIngredient: function ()
                             {
                                 var parent = this;
                                 $(parent.settings.modal.ingredient.container).unbind('click').click(function (e)
                                 {
                                     $.ajax
                                     ({
                                         type:    'POST',
                                         url:     $(this).attr('data-url'),
                                         data:    {},
                                         success: function (data)
                                                  {
                                                      $(parent.settings.modal.ingredient.content).html(data);
                                                      $(parent.settings.modal.ingredient.form).unbind('submit').submit(function (e)
                                                      {
                                                          e.preventDefault();
                                                          $.ajax
                                                          ({
                                                              type:    'POST',
                                                              url:     $(this).attr('action'),
                                                              data:    $(this).serialize(),
                                                              success: function (data)
                                                                       {
                                                                           if(typeof data.status == 'undefined')
                                                                           {
                                                                               $(parent.settings.modal.ingredient.content).html(data);
                                                                           }
                                                                           else
                                                                           {
                                                                               $(parent.settings.modal.ingredient.id).foundation('reveal', 'close');
                                                                               $(parent.settings.tabs.content).html(data.html);
                                                                           }
                                                                           parent.refresh();
                                                                       }
                                                          });
                                                      });
                                                      parent.refresh();
                                                  }
                                     });
                                 });
                             },
        getHtml:             function (data, selector)
                             {
                                 var parent = this;
                                 var $data = $($.parseHTML(data));
                                 return $data.filter(selector).add($data.find(selector)).html();
                             },
        refresh:             function ()
                             {
                                 var parent = this;
                                 parent.initSearch();
                                 parent.initModalAjax();
                                 parent.initModalCategory();
                                 parent.initModalProduct();
                                 parent.initModalIngredient();
                                 $(document).foundation();
                                 $(document).media();
                                 ddTree();
                             }
    };
    $.fn.card = function (options)
    {
        var settings = $.extend
        ({
            tabs:   {
                container: '.card .editor .editor-tabs a[data-url]',
                content:   '#editor-content'
            },
            search: {
                container: '.card .editor .search',
                content:   '#editor-search'
            },
            modal:  {
                ajax:       '.card .editor a[data-ajax]',
                category:   {
                    container: '.card .editor a[data-modal=category]',
                    content:   '#modal-editor .content',
                    form:      '#modal-editor .content form',
                    id:        '#modal-editor',
                    tab:       '#tab-category',
                    image:     'modal-image'
                },
                product:    {
                    container: '.card .editor a[data-modal=product]',
                    content:   '#modal-editor .content',
                    form:      '#modal-editor .content form',
                    id:        '#modal-editor',
                    tab:       '#tab-product',
                    image:     'modal-image'
                },
                ingredient: {
                    container: '.card .editor a[data-modal=ingredient]',
                    content:   '#modal-editor .content',
                    form:      '#modal-editor .content form',
                    id:        '#modal-editor',
                    tab:       '#tab-ingredient',
                    image:     'modal-image'
                }
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));