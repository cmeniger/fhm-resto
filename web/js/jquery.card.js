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
                                                      parent.refresh();
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
                                                          $(typeof data.content == 'undefined' ? parent.settings.tabs.content : data.content).html(data.html);
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
        initExternal:        function ()
                             {
                                 var parent = this;
                                 $(document).foundation();
                                 $(document).media();
                                 $(document).form({required: parent.settings.form.required});
                                 ddTree();
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
                                 parent.initExternal();
                             }
    };
    $.fn.card = function (options)
    {
        var settings = $.extend
        ({
            tabs:   {
                container: '.card.card-editor .editor-tabs a[data-url]',
                content:   '#editor-content'
            },
            search: {
                container: '.card.card-editor .search',
                content:   '#editor-search'
            },
            modal:  {
                ajax:       '.card.card-editor a[data-ajax]',
                category:   {
                    container: '.card.card-editor a[data-modal=category]',
                    content:   '#modal-editor-category .content',
                    form:      '#modal-editor-category .content form',
                    id:        '#modal-editor-category',
                    tab:       '#tab-category',
                    image:     'modal-image'
                },
                product:    {
                    container: '.card.card-editor a[data-modal=product]',
                    content:   '#modal-editor-product .content',
                    form:      '#modal-editor-product .content form',
                    id:        '#modal-editor-product',
                    tab:       '#tab-product',
                    image:     'modal-image'
                },
                ingredient: {
                    container: '.card.card-editor a[data-modal=ingredient]',
                    content:   '#modal-editor-ingredient .content',
                    form:      '#modal-editor-ingredient .content form',
                    id:        '#modal-editor-ingredient',
                    tab:       '#tab-ingredient',
                    image:     'modal-image'
                }
            },
            form:   {
                required: ''
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));