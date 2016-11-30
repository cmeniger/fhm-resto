<?php

namespace Fhm\FhmBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchType
 * @package Fhm\FhmBundle\Form\Type\Api
 */
class SearchType extends AbstractType
{
    protected $translation;

    /**
     * @param $domaine
     */
    public function setTranslation($domaine = 'fhm')
    {
        $options['translation_route'] = $domaine;
    }

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
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\FhmBundle\Document\Fhm',
                'translation_domain' => 'FhmFhmBundle',
                'cascade_validation' => true,
                'translation_route'=>'',
                'filter'=>'',
                'lang_visible'=>'',
                'lang_available'=>'',
                'grouping_visible'=>'',
                'grouping_available'=>'',
                'user_admin'=>''
            )
        );
    }

}