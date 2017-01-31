<?php
namespace Fhm\SliderBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\SliderBundle\Form\Type\Admin
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
            array('label' => $options['translation_route'] . '.admin.create.form.title')
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'] . '.admin.create.form.subtitle', 'required' => false)
        )->add(
            'resume',
            TextareaType::class,
            array(
                'label' => $options['translation_route'] . '.admin.create.form.resume',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'] . '.admin.create.form.content',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'add_global',
            CheckboxType::class,
            array('label' => $options['translation_route'] . '.admin.create.form.add_global', 'required' => false)
        )->add(
            'sort',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'] . '.admin.create.form.sort',
                'choices' => $this->sortChoices($options),
            )
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'] . '.admin.create.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'items',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'] . '.admin.create.form.items',
                'class' => 'FhmSliderBundle:SliderItem',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmSliderBundle:SliderItem');
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->remove('name')->remove('description');
    }

    /**
     * @return array
     */
    private function sortChoices($options)
    {
        return array(
            $options['translation_route'] . '.admin.sort.title.asc' => "title",
            $options['translation_route'] . '.admin.sort.title.desc' => "title desc",
            $options['translation_route'] . '.admin.sort.order.asc' => "order",
            $options['translation_route'] . '.admin.sort.order.desc' => "order desc",
            $options['translation_route'] . '.admin.sort.create.asc' => "date_create",
            $options['translation_route'] . '.admin.sort.create.desc' => "date_create desc",
            $options['translation_route'] . '.admin.sort.update.asc' => "date_update",
            $options['translation_route'] . '.admin.sort.update.desc' => "date_update desc",
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmSliderBundle',
                'cascade_validation' => true,
                'translation_route' => 'slider',
                'filter' => '',
                'user_admin' => '',
                'object_manager' => '',
                'map' => '',
            )
        );
    }
}