<?php
namespace Fhm\MediaBundle\Form\Type\Admin\Tag;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Repository\MediaTagRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UpdateType
 * @package Fhm\MediaBundle\Form\Type\Admin\Tag
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
        $builder
            ->add(
                'color',
                TextType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.color',
                    'attr' => array('class' => 'colorpicker'),
                    'required' => false,
                )
            )
            ->add(
                'private',
                CheckboxType::class,
                array('label' => $options['translation_route'].'.admin.update.form.private', 'required' => false)
            )
            ->add(
                'parent',
                DocumentType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.parent',
                    'class' => 'FhmMediaBundle:MediaTag',
                    'choice_label' => 'route',
                    'query_builder' => function (MediaTagRepository $dr) use ($options) {
                        return $dr->getFormFiltered($options['filter']);
                    },
                    'required' => false,
                )
            )
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global');
    }
}