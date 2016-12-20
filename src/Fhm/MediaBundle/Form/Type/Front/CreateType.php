<?php
namespace Fhm\MediaBundle\Form\Type\Front;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Fhm\MediaBundle\Repository\MediaTagRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CreateType
 * @package Fhm\MediaBundle\Form\Type\Front
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
            'name',
            TextType::class,
            array('label' => $options['translation_route'].'.front.create.form.name', 'required' => false)
        )->add(
            'file',
            FileType::class,
            array('label' => $options['translation_route'].'.front.create.form.file')
        )->add(
            'tag',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.front.create.form.tag',
                'mapped' => false,
                'required' => false,
            )
        )->add(
            'parent',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.front.create.form.parent',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function (MediaTagRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'mapped' => false,
                'required' => false,
            )
        )->add(
            'tags',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.front.create.form.tags',
                'class' => 'FhmMediaBundle:MediaTag',
                'choice_label' => 'route',
                'query_builder' => function (MediaTagRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->remove('seo_title')->remove('seo_description')->remove('seo_keywords')->remove('languages')->remove(
            'grouping'
        )->remove('share')->remove('global')->remove('submitNew')->remove('submitDuplicate')->remove(
            'submitQuit'
        )->remove('submitConfig');
    }
}