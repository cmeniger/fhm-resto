<?php

namespace Fhm\FhmBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExportType
 * @package Fhm\FhmBundle\Form\Type\Admin
 */
class ExportType extends AbstractType
{
    protected $instance;

    protected $translation;

    /**
     * @param $domaine
     */
    public function setTranslation($domaine)
    {
        $this->translation = $domaine;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $this->instance = $data['instance'];
        $builder
            ->add(
                'submit',
                SubmitType::class,
                array('label' => $this->instance->translation.'.admin.export.form.submit')
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmExport';
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