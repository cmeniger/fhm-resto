<?php

namespace Project\ProductBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProductExtension extends \Twig_Extension
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'productBloc' => new \Twig_Filter_Method($this, 'getBloc'),
            'small'     => new \Twig_Filter_Method($this, 'getSmall'),
            'medium'    => new \Twig_Filter_Method($this, 'getMedium'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'product_extension';
    }

    /**
     * @param \Project\ProductBundle\Entity\Product $product
     * @param object                          $instance
     *
     * @return string
     */
    public function getBloc($product, $instance)
    {
        // Status
        $status  = "";
        $status .= $product->getShare() && $product->hasGrouping($instance->grouping->used) ? "<span class='status regular round label' title='" . $this->container->get('translator')->trans('fhm.status.share', array(), 'FhmFhmBundle') . "'><i class='fa fa-users fa-fw'></i></span>" : '';
        $status .= !$product->getActive() ? "<span class='status warning round label' title='" . $this->container->get('translator')->trans('fhm.status.disable', array(), 'FhmFhmBundle') . "'><i class='fa fa-eye-slash fa-fw'></i></span>" : '';
        $status .= $product->getDelete() ? "<span class='status alert round label' title='" . $this->container->get('translator')->trans('fhm.status.delete', array(), 'FhmFhmBundle') . "'><i class='fa fa-trash fa-fw'></i></span>" : '';
        // Actions
        if($product->getShare() && !$product->hasGrouping($instance->grouping->used) && $instance->user->admin)
        {
            $actions  = "";
            $actions .= "<a href='" . $this->container->get('router')->generate('fhm_admin_product_detail', array('id' => $product->getId())) . "' title='" . $this->container->get('translator')->trans('product.admin.index.table.action.detail', array(), 'FhmProductBundle') . "'><i class='fa fa-gear fa-fw'></i></a>";
            $actions .= "<a href='" . $this->container->get('router')->generate('fhm_admin_product_duplicate', array('id' => $product->getId())) . "' title='" . $this->container->get('translator')->trans('product.admin.index.table.action.duplicate', array(), 'FhmProductBundle') . "'><i class='fa fa-files-o fa-fw'></i></a>";
        }
        else
        {
            $actions  = "";
            $actions .= "<a href='" . $this->container->get('router')->generate('fhm_admin_product_detail', array('id' => $product->getId())) . "' title='" . $this->container->get('translator')->trans('product.admin.index.table.action.detail', array(), 'FhmProductBundle') . "'><i class='fa fa-gear fa-fw'></i></a>";
        }



//        elseif()
//            $this->container->generateUrl('fhm_admin_product_detail', array('id' => $product->getId()))
//
//
//        {% if document.getShare() and not document.hasGrouping(instance.grouping.used) and not instance.user.admin %}
//        {% block content_data_action_share %}
//        <td>
//                                            {% block content_data_action_share_links %}
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_detail', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.detail')|trans }}"><i class="fa fa-gear"></i></a>
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_duplicate', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.duplicate')|trans }}"><i class="fa fa-files-o"></i></a>
//                                            {% endblock %}
//                                        </td>
//                                    {% endblock %}
//                                {% elseif document.getDelete() %}
//                                    {% block content_data_action_delete %}
//                                        <td>
//                                            {% block content_data_action_delete_links %}
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_detail', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.detail')|trans }}"><i class="fa fa-gear"></i></a>
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_update', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.update')|trans }}"><i class="fa fa-pencil"></i></a>
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_duplicate', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.duplicate')|trans }}"><i class="fa fa-files-o"></i></a>
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_undelete', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.undelete')|trans }}" onclick="return confirm('{{ (instance.translation ~ '.admin.index.table.confirm.undelete')|trans }}')"><i class="fa fa-undo"></i></a>
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_delete', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.delete')|trans }}" onclick="return confirm('{{ (instance.translation ~ '.admin.index.table.confirm.delete')|trans }}')"><i class="fa fa-trash"></i></a>
//                                            {% endblock %}
//                                        </td>
//                                    {% endblock %}
//                                {% else %}
//                                    {% block content_data_action %}
//                                        <td>
//                                            {% block content_data_action_links %}
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_detail', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.detail')|trans }}"><i class="fa fa-gear"></i></a>
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_update', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.update')|trans }}"><i class="fa fa-pencil"></i></a>
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_duplicate', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.duplicate')|trans }}"><i class="fa fa-files-o"></i></a>
//                                                {% if document.getActive() %}
//                                                    <a href="{{ path('fhm_admin_' ~ instance.route ~ '_deactivate', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.deactivate')|trans }}" onclick="return confirm('{{ (instance.translation ~ '.admin.index.table.confirm.deactivate')|trans }}')"><i class="fa fa-eye-slash"></i></a>
//                                                {% else %}
//                                                    <a href="{{ path('fhm_admin_' ~ instance.route ~ '_activate', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.activate')|trans }}" onclick="return confirm('{{ (instance.translation ~ '.admin.index.table.confirm.activate')|trans }}')"><i class="fa fa-eye"></i></a>
//                                                {% endif %}
//                                                <a href="{{ path('fhm_admin_' ~ instance.route ~ '_delete', {'id': document.getId()}) }}" title="{{ (instance.translation ~ '.admin.index.table.action.delete')|trans }}" onclick="return confirm('{{ (instance.translation ~ '.admin.index.table.confirm.delete')|trans }}')"><i class="fa fa-trash-o"></i></a>
//                                            {% endblock %}
//                                        </td>
//                                    {% endblock %}
//                                {% endif %}
        $actions = "<a href='#' title='toto'><i class='fa fa-file'></i></a>";
        return
            "<div class='data' product-id='" . $product->getId() . "' title='" . $product->getName() . "'></div>";


//            "<a href='#' class='data' product-id='" . $product->getId() . "' title='" . $product->getName() . "'>" .
//            "<div class='header'><span><i class='fa fa-file'></i></span>" . $product->getName() . "</span>" .
//            "<div class='footer'><a style='display:inline;' href='#' title='toto'><i class='fa fa-file'></i></a></span>" .
//            "</a>";
    }

    /**
     * @param \Project\ProductBundle\Entity\Product $product
     * @param string                          $default
     *
     * @return string
     */
    public function getSmall($product, $default = null)
    {
        if(is_object($product) && !is_null($product->getUrl()) && $product->getUrl() != '#')
        {
            return $product->getUrl();
        }
        else
        {
            $file = $product ? '/datas/product/' . $product->getId() . '/small.' . $product->getExtension() : '';

            return ($file && file_exists('../web' . $file)) ? $file : $default;
        }
    }

    /**
     * @param \Project\ProductBundle\Entity\Product $product
     * @param string                          $default
     *
     * @return string
     */
    public function getMedium($product, $default = null)
    {
        if(is_object($product) && !is_null($product->getUrl()) && $product->getUrl() != '#')
        {
            return $product->getUrl();
        }
        else
        {
            $file = $product ? '/datas/product/' . $product->getId() . '/medium.' . $product->getExtension() : '';

            return ($file && file_exists('../web' . $file)) ? $file : $default;
        }
    }
}
