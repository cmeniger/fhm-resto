<?php
namespace Fhm\NotificationBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
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
        $this->setTranslation('notification');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'content',
                TextareaType::class,
                array('label' => $this->translation.'.admin.create.form.content', 'attr' => array('class' => 'editor'))
            )
            ->add(
                'user',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.create.form.user',
                    'class' => 'FhmUserBundle:User',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr) {
                        return $dr->getFormEnable();
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\NotificationBundle\Document\Notification',
                'translation_domain' => 'FhmNotificationBundle',
                'cascade_validation' => true,
            )
        );
    }
}