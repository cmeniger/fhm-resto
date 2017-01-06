<?php
namespace Fhm\FhmBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\FhmBundle\Form\Type\Admin
 */
class UpdateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.name')
        )->add(
            'description',
            TextareaType::class,
            array('label' => $options['translation_route'].'.admin.update.form.description', 'required' => false)
        )->add(
            'seo_title',
            TextType::class,
            array('label' => 'fhm.seo.title', 'translation_domain' => 'FhmFhmBundle', 'required' => false)
        )->add(
            'seo_description',
            TextType::class,
            array('label' => 'fhm.seo.description', 'translation_domain' => 'FhmFhmBundle', 'required' => false)
        )->add(
            'seo_keywords',
            TextType::class,
            array('label' => 'fhm.seo.keywords', 'translation_domain' => 'FhmFhmBundle', 'required' => false)
        )->add(
            'active',
            CheckboxType::class,
            array('label' => $options['translation_route'].'.admin.update.form.active', 'required' => false)
        )->add(
            'submitSave',
            SubmitType::class,
            array('label' => $options['translation_route'].'.admin.update.form.submit.save')
        )->add(
            'submitNew',
            SubmitType::class,
            array('label' => $options['translation_route'].'.admin.update.form.submit.new')
        )->add(
            'submitDuplicate',
            SubmitType::class,
            array('label' => $options['translation_route'].'.admin.update.form.submit.duplicate')
        )->add(
            'submitQuit',
            SubmitType::class,
            array('label' => $options['translation_route'].'.admin.update.form.submit.quit')
        )->add(
            'submitConfig',
            SubmitType::class,
            array('label' => $options['translation_route'].'.admin.update.form.submit.config')
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmUpdate';
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
                'user_admin' => '',
            )
        );
    }
}