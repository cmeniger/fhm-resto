<?php

namespace Fhm\NewsletterBundle\Form\Type\Api;

use Fhm\FhmBundle\Form\Type\Api\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\NewsletterBundle\Form\Type\Api
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
            ->add('email', EmailType::class, array('label' => $options['translation_route'] . '.admin.create.form.email'))
            ->remove('name')
            ->remove('description')
            ->remove('submitSave')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => 'Fhm\NewsletterBundle\Document\Newsletter',
                'translation_domain' => 'FhmNewsletterBundle',
                'cascade_validation' => true,
                'translation_route'  => 'newsletter',
                'user_admin'         => '',
            )
        );
    }
}