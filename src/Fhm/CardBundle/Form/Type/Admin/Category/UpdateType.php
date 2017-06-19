<?php

namespace Fhm\CardBundle\Form\Type\Admin\Category;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\CardBundle\Form\Type\Admin\Category
 */
class UpdateType extends FhmType
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
                    'label'    => $options['translation_route'] . '.admin.update.form.price',
                    'currency' => '',
                    'required' => false,
                )
            )
            ->add(
                'currency',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.update.form.currency',
                    'required' => false,
                )
            )
            ->add(
                'order',
                IntegerType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.update.form.order',
                    'required' => false,
                )
            )
            ->add(
                'menu',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.update.form.menu',
                    'required' => false,
                )
            )
            ->add(
                'default',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.update.form.default',
                    'required' => false,
                )
            )->add(
                'image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.admin.update.form.image',
                    'filter'   => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'card',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.update.form.card',
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
                'products',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.update.form.products',
                    'class'         => 'FhmCardBundle:CardProduct',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardProduct');

                        return $dr->getFormEnable();
                    },
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false,
                )
            )
            ->add(
                'parents',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.update.form.parents',
                    'class'         => 'FhmCardBundle:CardCategory',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardCategory');

                        return $dr->getFormEnable();
                    },
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false,
                )
            )
            ->add(
                'sons',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.admin.update.form.sons',
                    'class'         => 'FhmCardBundle:CardCategory',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardCategory');

                        return $dr->getFormEnable();
                    },
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false,
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
                'translation_route'  => 'card.category',
                'user_admin'         => '',
                'object_manager'     => ''
            )
        );
    }
}