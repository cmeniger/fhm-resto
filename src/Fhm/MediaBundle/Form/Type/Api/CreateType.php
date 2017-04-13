<?php
namespace Fhm\MediaBundle\Form\Type\Api;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Document\Repository\MediaTagRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\MediaBundle\Form\Type\Api
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
                'parent',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'].'.admin.create.form.parent',
                    'class' => 'FhmMediaBundle:MediaTag',
                    'choice_label' => 'route',
                    'query_builder' => function (MediaTagRepository $dr) use ($options) {
//                        return $dr->setRoot($this->root)->getFormFiltered($options['filter']);
                        return $dr->getFormFiltered();
                    },
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'tags',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'].'.admin.create.form.tags',
                    'class' => 'FhmMediaBundle:MediaTag',
                    'choice_label' => 'route',
                    'query_builder' => function (MediaTagRepository $dr) use ($options) {
//                        return $dr->setRoot($this->root)->getFormFiltered($options['filter']);
                        return $dr->getFormFiltered();
                    },
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                )
            )
            ->remove('private')
            ->remove('active')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmMediaBundle',
                'cascade_validation' => true,
                'translation_route' => 'media',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
}