<?php

namespace Fhm\CardBundle\Form\Type\Admin\Product;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\CardBundle\Form\Type\Admin\Product
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'price',
                MoneyType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.price',
                    'currency' => '',
                    'required' => false,
                )
            )
            ->add(
                'currency',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.currency',
                    'required' => false,
                )
            )
            ->add(
                'order',
                IntegerType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.order',
                    'required' => false,
                )
            )
            ->add(
                'forward',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.forward',
                    'required' => false,
                )
            )
            ->add(
                'caption',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.caption',
                    'required' => false,
                )
            )
            ->add(
                'default',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.default',
                    'required' => false,
                )
            )
            ->add(
                'image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.create.form.image',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'card',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.card',
                    'class'         => 'FhmCardBundle:Card',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:Card');

                        return $dr->getFormEnable();
                    },
                    'required'      => false,
                )
            )
            ->add(
                'categories',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.categories',
                    'class'         => 'FhmCardBundle:CardCategory',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardCategory');

                        return $dr->getFormEnable();
                    },
                    'multiple'      => true,
                    'required'      => false,
                    'by_reference'  => false,
                )
            )
            ->add(
                'ingredients',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.create.form.ingredients',
                    'class'         => 'FhmCardBundle:CardIngredient',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardIngredient');

                        return $dr->getFormEnable();
                    },
                    'multiple'      => true,
                    'required'      => false,
                    'by_reference'  => false,
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
                'translation_route'  => 'card.product',
                'user_admin'         => '',
                'object_manager'     => ''
            )
        );
    }

}