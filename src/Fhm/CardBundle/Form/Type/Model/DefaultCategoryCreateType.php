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

class DefaultCategoryCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => $options['translation_route'] . '.api.create.form.name'
                ))
            ->add(
                'description',
                TextareaType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.description',
                    'required' => false
                ))
            ->add(
                'price',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.price',
                    'required' => false
                ))
            ->add(
                'currency',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.currency',
                    'required' => false
                ))
            ->add(
                'order',
                IntegerType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.order',
                    'required' => false
                ))
            ->add(
                'menu',
                CheckboxType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.menu',
                    'required' => false
                ))
            ->add(
                'image',
                MediaType::class,
                array(
                    'label'    => $options['translation_route'] . '.api.create.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                ))
            ->add(
                'parents',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.api.create.form.parents',
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
                'products',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.api.create.form.products',
                    'class'         => 'FhmCardBundle:CardProduct',
                    'choice_label'  => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardProduct');

                        return $dr->setSort('route')->getFormCard($options['card']);
                    },
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false
                ))
            ->add(
                'submitSave',
                SubmitType::class,
                array(
                    'label' => $options['translation_route'] . '.api.create.form.submit.save'
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
                'translation_route'  => 'card.category',
                'cascade_validation' => true,
                'user_admin'         => '',
                'object_manager'     => '',
                'card'               => ''
            )
        );
    }
}