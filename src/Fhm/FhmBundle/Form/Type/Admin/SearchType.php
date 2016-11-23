<?php

namespace Fhm\FhmBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', TextType::class, array('required' => false, 'attr' => array('placeholder' => $this->instance->translation . '.admin.index.form.search', 'data-type' => 'list')));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmSearch';
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults
            (
                array(
                    'data_class'         => null,
                    'translation_domain' => $this->instance->domain,
                    'cascade_validation' => true
                )
            );
    }
}