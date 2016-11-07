<?php
namespace Fhm\FhmBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutocompleteType extends AbstractType
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'property'           => 'name',
            'cascade_validation' => true,
            'url'                => '',
            'attr'               => array('placeholder' => $this->container->get('translator')->trans('fhm.autocomplete.placeholder', array(), 'FhmFhmBundle'))
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormView      $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array                                 $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(array_key_exists('url', $options))
        {
            $route             = is_array($options['url']) ? $options['url'][0] : $options['url'];
            $parameters        = is_array($options['url']) ? $options['url'][1] : array();
            $view->vars['url'] = $this->container->get('router')->generate($route, $parameters);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'autocomplete';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'document';
    }
}