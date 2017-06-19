<?php

namespace Fhm\CardBundle\Form\Type\Model;

use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultIngredientUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $options['translation_route'] . '.api.update.form.name'))
            ->add('description', 'textarea', array('label' => $options['translation_route'] . '.api.update.form.description', 'required' => false))
            ->add('order', 'integer', array('label' => $options['translation_route'] . '.api.update.form.order', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $options['translation_route'] . '.api.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('products',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label'         => $options['translation_route'] . '.api.update.form.products',
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
            ->add('submitSave', 'submit', array('label' => $options['translation_route'] . '.api.update.form.submit.save'));
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
                'translation_route'  => 'card.ingredient',
                'cascade_validation' => true,
                'user_admin'         => '',
                'object_manager'     => '',
                'card'               => ''
            )
        );
    }
}