<?php
namespace Fhm\NotificationBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\UserBundle\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\NotificationBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'content',
                TextareaType::class,
                array('label' => $options['translation_route'].'.admin.create.form.content',
                      'attr' => array('class' => 'editor'))
            )
            ->add(
                'user',
                DocumentType::class,
                array(
                    'label' => $options['translation_route'].'.admin.create.form.user',
                    'class' => 'FhmUserBundle:User',
                    'choice_label' => 'name',
                    'query_builder' => function (UserRepository $dr)  use ($options) {
                        return $dr->getFormEnable($options['filter']);
                    },
                )
            )
            ->remove('name')
            ->remove('description')
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global')
            ->remove('active');
    }
}