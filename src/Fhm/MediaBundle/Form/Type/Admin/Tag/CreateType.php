<?php
namespace Fhm\MediaBundle\Form\Type\Admin\Tag;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Repository\MediaTagRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\MediaBundle\Form\Type\Admin\Tag
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
        $builder->add(
            'color',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.color',
                'attr' => array('class' => 'colorpicker'),
                'required' => false,
            )
        )->add(
            'private',
            CheckboxType::class,
            array('label' => $options['translation_route'].'.admin.create.form.private', 'required' => false)
        )->add(
            'parent',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.parent',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:MediaTag');
                    return $dr->getFormFiltered();
                },
                'required' => false,
            )
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
        )->remove('share')->remove('global');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmMediaBundle',
                'cascade_validation' => true,
                'translation_route' => 'media.tag',
                'user_admin' => '',
                'object_manager'=>''
            )
        );
    }
}