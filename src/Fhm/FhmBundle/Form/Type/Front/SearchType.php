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
    protected $instance;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $this->instance = $data['instance'];
        $builder->add(
            'search',
            TextType::class,
            array(
                'required' => false,
                'attr' => array(
                    'placeholder' => $this->instance->translation.'.front.index.form.search',
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
                'data_class' => null,
                'translation_domain' => $this->instance->domain,
                'cascade_validation' => true,
            )
        );
    }

}