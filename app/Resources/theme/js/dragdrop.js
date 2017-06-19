/**
 * Drag and Drop Sort
 */
function ddSort()
{
    $('.dd-sort').nestable
    ({
        listNodeName:    'ol',
        itemNodeName:    'li',
        rootClass:       'dd-sort',
        listClass:       'dd-sort-list',
        itemClass:       'dd-sort-item',
        dragClass:       'dd-sort-drag',
        handleClass:     'dd-sort-handle',
        collapsedClass:  'dd-sort-collapsed',
        placeClass:      'dd-sort-placeholder',
        noDragClass:     'dd-sort-nodrag',
        emptyClass:      'dd-sort-empty',
        expandBtnHTML:   '',
        collapseBtnHTML: '',
        maxDepth:        1
    }).on('change', function (e)
    {
        if(typeof $(this).attr('data-url') != 'undefined')
        {
            var list = e.length ? e : $(e.target);
            $.ajax
            ({
                type:    'POST',
                url:     $(this).attr('data-url'),
                data:    {
                    master: $(this).attr('data-id'),
                    list:   JSON.stringify(list.nestable('serialize'))
                },
                success: function (data)
                         {
                         }
            });
        }
    });
    $('.dd-sort-module').on('click', function ()
    {
        var obj = $(this);
        $('.dd-sort-module').removeClass('select').removeClass('load');
        obj.addClass('load');
        $.ajax
        ({
            type:    'POST',
            url:     $(this).attr('data-url'),
            data:    {
                module: $(this).attr('data-module')
            },
            success: function (data)
                     {
                         obj.removeClass('load').addClass('select');
                         $('#dd-sort-data').html(data);
                     }
        });
    });
}
/**
 * Drag and Drop Tree
 */
function ddTree()
{
    $('.dd-tree').each(function ()
    {
        $(this).nestable
        ({
            listNodeName:    'ol',
            itemNodeName:    'li',
            rootClass:       'dd-tree',
            listClass:       'dd-tree-list',
            itemClass:       'dd-tree-item',
            dragClass:       'dd-tree-drag',
            handleClass:     'dd-tree-handle',
            collapsedClass:  'dd-tree-collapsed',
            placeClass:      'dd-tree-placeholder',
            noDragClass:     'dd-tree-nodrag',
            emptyClass:      'dd-tree-empty',
            expandBtnHTML:   '',
            collapseBtnHTML: '',
            maxDepth:        $(this).attr('data-depth') == "undefined" ? 99 : $(this).attr('data-depth')
        }).on('change', function (e)
        {
            if(typeof $(this).attr('data-url') != 'undefined')
            {
                var list = e.length ? e : $(e.target);
                $.ajax
                ({
                    type:    'POST',
                    url:     $(this).attr('data-url'),
                    data:    {
                        master: $(this).attr('data-id'),
                        list:   JSON.stringify(list.nestable('serialize'))
                    },
                    success: function (data)
                             {
                             }
                });
            }
        });
    });
}
/**
 * Drag and Drop List
 */
function ddList()
{
    $('.dd-list').each(function ()
    {
        var list = $(this);
        var group = (typeof list.attr('data-group') == 'undefined') ? 1 : list.attr('data-group');
        list.nestable
            ({
                listNodeName:    'ol',
                itemNodeName:    'li',
                rootClass:       'dd-list',
                listClass:       'dd-list-list',
                itemClass:       'dd-list-item',
                dragClass:       'dd-list-drag',
                handleClass:     'dd-list-handle',
                collapsedClass:  'dd-list-collapsed',
                placeClass:      'dd-list-placeholder',
                noDragClass:     'dd-list-nodrag',
                emptyClass:      'dd-list-empty',
                expandBtnHTML:   '',
                collapseBtnHTML: '',
                group:           group
            })
            .on('change', function (e)
            {
                if(typeof $(this).attr('data-url') != 'undefined')
                {
                    var data = e.length ? e : $(e.target);
                    $.ajax
                    ({
                        type:    'POST',
                        url:     $(this).attr('data-url'),
                        data:    {
                            id:   $(this).attr('data-id'),
                            list: JSON.stringify(data.nestable('serialize'))
                        },
                        success: function (data)
                                 {
                                 }
                    });
                }
            })
            .on('lostItem', function (e)
            {
                $(this).trigger('change');
            })
            .on('gainedItem', function (e)
            {
                $(this).trigger('change');
            });
    });
}
function addLink()
{
    $('#FhmAddLink_form').on('submit', function (e)
    {
        e.preventDefault();
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var datas = $form.serialize();
        $.ajax({
            url:      $form.attr('action'),
            type:     $form.attr('method'),
            dataType: 'json',
            data:     datas,
            success:  function (data)
                      {
                          var modal = document.getElementById('addData');
                          var box = modal.getAttribute("box");
                          var elem = document.getElementById(box);
                          elem.value = data.link;
                          var module = document.getElementById('FhmAdd_module');
                          module.value = data.module;
                          var dataElem = document.getElementById('FhmAdd_data');
                          dataElem.value = data.id;
                          $('#addData').foundation('reveal', 'close');
                          $('#addDataForm').foundation('reveal', 'open');
                      },
            error:    function (resultat, statut, erreur)
                      {
                          console.log(statut);
                      }
        });
    });
}
ddSort();
ddTree();
ddList();
addLink();