<?php
namespace Fhm\EventBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\EventBundle\Form\Type\Admin\Group\AddType;
use Fhm\EventBundle\Repository\EventGroupRepository;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\EventBundle\Form\Type\Admin
 */
class UpdateType extends FhmType
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
            array('label' => $options['translation_route'].'.admin.update.form.title')
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.subtitle', 'required' => false)
        )->add(
            'resume',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.resume',
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.content',
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'date_start',
            DateTimeType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.start',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => array('class' => 'datetimepicker'),
            )
        )->add(
            'date_end',
            DateTimeType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.end',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => array('class' => 'datetimepicker'),
            )
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'eventgroups',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.eventgroups',
                'class' => 'FhmEventBundle:EventGroup',
                'choice_label' => 'name',
                'query_builder' => function (EventGroupRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->remove('name')->remove('description');
    }

}