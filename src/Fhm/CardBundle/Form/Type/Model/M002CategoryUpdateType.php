<?php

namespace Fhm\CardBundle\Form\Type\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class M002CategoryUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $options['translation_route'] . '.api.update.form.name'))
            ->add('description', 'textarea', array('label' => $options['translation_route'] . '.api.update.form.description', 'required' => false))
            ->add('order', 'text', array('label' => $options['translation_route'] . '.api.update.form.order', 'required' => false))
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
                'translation_route'  => 'card.category',
                'cascade_validation' => true,
                'user_admin'         => '',
                'object_manager'     => '',
                'card'               => ''
            )
        );
    }
}