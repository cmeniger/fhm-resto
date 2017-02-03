<?php
namespace Fhm\NotificationBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\UserBundle\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\NotificationBundle\Form\Type\Admin
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.content',
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'user',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.update.form.user',
                'class' => 'FhmUserBundle:User',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmUserBundle:User');
                    return $dr->getFormEnable($options['filter']);
                },
            )
        )->remove('name')->remove('description')->remove('seo_title')->remove('seo_description')->remove(
            'seo_keywords'
        )->remove('languages')->remove('grouping')->remove('share')->remove('global')->remove('active');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'FhmNotificationBundle:Notification',
                'translation_domain' => 'FhmNotificationBundle',
                'cascade_validation' => true,
                'translation_route' => 'notification',
                'filter' => '',
                'user_admin' => '',
                'map' => '',
            )
        );
    }
}