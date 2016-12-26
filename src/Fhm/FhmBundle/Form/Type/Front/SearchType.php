<?php
namespace Fhm\FhmBundle\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchType
 * @package Fhm\FhmBundle\Form\Type\Front
 */
class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'search',
            TextType::class,
            array(
                'required' => false,
                'attr' => array(
                    'placeholder' => $options['translation_route'].'.front.index.form.search',
                    'data-type' => 'list',
                ),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'FhmSearch';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('translation_domain' => 'FhmFhmBundle', 'translation_route' => 'fhm'));
    }

}