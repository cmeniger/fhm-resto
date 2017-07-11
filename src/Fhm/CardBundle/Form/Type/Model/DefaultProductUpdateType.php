<?php

namespace Fhm\CardBundle\Form\Type\Model;

use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultProductUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => $options['translation_route'] . '.api.update.form.name'
                ))
            ->add(
                'description',
                TextareaType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.update.form.description',
                    'required' => false
                ))
            ->add(
                'price',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.update.form.price',
                    'required' => false
                ))
            ->add(
                'currency',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.update.form.currency',
                    'required' => false
                ))
            ->add(
                'order',
                IntegerType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.update.form.order',
                    'required' => false
                ))
            ->add(
                'forward',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.update.form.forward',
                    'required' => false
                ))
            ->add(
                'caption',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.update.form.caption',
                    'required' => false
                ))
            ->add(
                'image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.update.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                ))
            ->add(
                'categories',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.api.update.form.categories',
                    'class'         => 'FhmCardBundle:CardCategory',
                    'choice_label'  => 'route',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardCategory');

                        return $dr->setSort('route')->getFormParents($options['card']);
                    },
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false
                ))
            ->add(
                'ingredients',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.api.update.form.ingredients',
                    'class'         => 'FhmCardBundle:CardIngredient',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardIngredient');

                        return $dr->setSort('route')->getFormCard($options['card']);
                    },
                    'multiple'      => true,
                    'required'      => false,
                    'by_reference'  => false
                ))
            ->add(
                'submitSave',
                SubmitType::class,
                array(
                    'label' => $options['translation_route'] . '.api.update.form.submit.save'
                ));
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
                'translation_route'  => 'card.product',
                'cascade_validation' => true,
                'user_admin'         => '',
                'object_manager'     => '',
                'card'               => ''
            )
        );
    }
}