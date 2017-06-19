<?php
namespace Fhm\CardBundle\Form\Type\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class M001CategoryCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $options['translation_route'] . '.api.create.form.name'))
            ->add('description', 'textarea', array('label' => $options['translation_route'] . '.api.create.form.description', 'required' => false))
            ->add('price', 'text', array('label' => $options['translation_route'] . '.api.create.form.price', 'required' => false))
            ->add('currency', 'text', array('label' => $options['translation_route'] . '.api.create.form.currency', 'required' => false))
            ->add('menu', 'checkbox', array('label' => $options['translation_route'] . '.api.create.form.menu', 'required' => false))
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