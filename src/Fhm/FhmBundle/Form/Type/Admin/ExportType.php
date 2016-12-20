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
            'submit',
            SubmitType::class,
            array('label' => $options['translation_route'].'.admin.export.form.submit')
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
                'data_class' => 'Fhm\FhmBundle\Document\Fhm',
                'translation_domain' => 'FhmFhmBundle',
                'cascade_validation' => true,
                'translation_route' => '',
                'filter' => '',
                'lang_visible' => '',
                'lang_available' => '',
                'grouping_visible' => '',
                'grouping_available' => '',
                'user_admin' => '',
            )
        );
    }
}