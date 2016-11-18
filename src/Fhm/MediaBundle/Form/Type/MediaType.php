<?php
namespace Fhm\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaType extends AbstractType
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
            'class'              => 'FhmMediaBundle:Media',
            'property'           => 'name',
            'cascade_validation' => true,
            'filter'             => '',
            'root'               => '',
            'private'            => true
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormView      $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array                                 $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(array_key_exists('filter', $options))
        {
            $view->vars['filter']  = $options['filter'];
            $view->vars['root']    = $options['root'];
            $view->vars['private'] = $options['private'];
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'media';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'document';
    }
}