<?php
namespace Fhm\EventBundle\Form\Type\Admin;

use Fhm\EventBundle\Form\Type\Admin\Group\AddType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\EventBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'title',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.title')
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.create.form.subtitle', 'required' => false)
        )->add(
            'resume',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.resume',
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.content',
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'date_start',
            DateTimeType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.start',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => array('class' => 'datetimepicker'),
            )
        )->add(
            'date_end',
            DateTimeType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.end',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => array('class' => 'datetimepicker'),
            )
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'eventgroups',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.eventgroups',
                'class' => 'FhmEventBundle:EventGroup',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmEventBundle:EventGroup');
                    return $dr->getFormEnable();
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->remove('name')->remove('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmEventBundle',
                'cascade_validation' => true,
                'translation_route' => 'event',
                'user_admin' => '',
                'object_manager'=> ''
            )
        );
    }
}