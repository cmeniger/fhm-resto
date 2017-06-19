<?php

namespace Fhm\CardBundle\Form\Type\Model;

use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultCategoryCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $options['translation_route'] . '.api.create.form.name'))
            ->add('description', 'textarea', array('label' => $options['translation_route'] . '.api.create.form.description', 'required' => false))
            ->add('price', 'text', array('label' => $options['translation_route'] . '.api.create.form.price', 'required' => false))
            ->add('currency', 'text', array('label' => $options['translation_route'] . '.api.create.form.currency', 'required' => false))
            ->add('order', 'integer', array('label' => $options['translation_route'] . '.api.create.form.order', 'required' => false))
            ->add('menu', 'checkbox', array('label' => $options['translation_route'] . '.api.create.form.menu', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $options['translation_route'] . '.api.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('parents',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.api.create.form.parents',
                    'class'         => 'FhmCardBundle:CardCategory',
                    'property'      => 'route',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardCategory');

                        return $dr->setSort('route')->getFormParents($options['card']);
                    },
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false
                ))
            ->add('products',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.api.create.form.products',
                    'class'         => 'FhmCardBundle:CardProduct',
                    'property'      => 'name',
                    'query_builder' => function () use ($options)
                    {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCardBundle:CardProduct');

                        return $dr->setSort('route')->getFormCard($options['card']);
                    },
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false
                ))
            ->add('submitSave', 'submit', array('label' => $options['translation_route'] . '.api.create.form.submit.save'));
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