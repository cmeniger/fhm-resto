<?php
namespace Fhm\CardBundle\Form\Type\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class M001ProductUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $options['translation_route'] . '.api.update.form.name'))
            ->add('ingredient', 'text', array('label' => $options['translation_route'] . '.api.update.form.ingredient', 'required' => false, "mapped" => false))
            ->add('price', 'text', array('label' => $options['translation_route'] . '.api.update.form.price', 'required' => false))
            ->add('currency', 'text', array('label' => $options['translation_route'] . '.api.update.form.currency', 'required' => false))
            ->add('forward', 'checkbox', array('label' => $options['translation_route'] . '.api.update.form.forward', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $options['translation_route'] . '.api.update.form.image',
                'filter'   => 'image/*',
                'required' => false
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
                'translation_route'  => 'card.product',
                'cascade_validation' => true,
                'user_admin'         => '',
                'object_manager'     => ""
            )
        );
    }
}