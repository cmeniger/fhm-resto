<?php
namespace Fhm\MediaBundle\Form\Type\Admin\Tag;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Repository\MediaTagRepository;
use Symfony\Component\Form\FormBuilderInterface;

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
            'text',
            array(
                'label' => $options['translation_route'].'.admin.create.form.color',
                'attr' => array('class' => 'colorpicker'),
                'required' => false,
            )
        )->add(
            'private',
            'checkbox',
            array('label' => $options['translation_route'].'.admin.create.form.private', 'required' => false)
        )->add(
            'parent',
            'document',
            array(
                'label' => $options['translation_route'].'.admin.create.form.parent',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function (MediaTagRepository $dr) {
                    return $dr->getFormFiltered();
                },
                'required' => false,
            )
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
        )->remove('share')->remove('global');
    }
}