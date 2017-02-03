<?php
namespace Fhm\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MediaType
 *
 * @package Fhm\MediaBundle\Form\Type
 */
class MediaType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'FhmMediaBundle:Media',
                'choice_label' => 'name',
                'cascade_validation' => true,
                'filter' => '',
                'root' => '',
                'private' => true,
            )
        );
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('filter', $options)) {
            $view->vars['filter'] = $options['filter'];
            $view->vars['root'] = $options['root'];
            $view->vars['private'] = $options['private'];
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'media';
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return DocumentType::class;
    }
}