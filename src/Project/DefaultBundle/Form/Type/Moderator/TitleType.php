<?php

namespace Project\DefaultBundle\Form\Type\Moderator;

use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TitleType
 *
 * @package Project\DefaultBundle\Form\Type\Moderator
 */
class TitleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'section_title',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.title',
                    'mapped'   => false,
                    'required' => false
                )
            )
            ->add(
                'section_subtitle',
                TextType::class,
                array(
                    'label'    => $options['translation_route'] . '.moderator.form.site.subtitle',
                    'mapped'   => false,
                    'required' => false
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ModeratorTitle';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => '',
                'translation_domain' => 'ProjectDefaultBundle',
                'cascade_validation' => true,
                'translation_route'  => 'project',
                'user_admin'         => '',
                'object_manager'     => ''
            )
        );
    }
}