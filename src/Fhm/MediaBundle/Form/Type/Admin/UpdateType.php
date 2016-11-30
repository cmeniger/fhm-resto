<?php
namespace Fhm\MediaBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('media');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'file',
                FileType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.file',
                    'required' => false,
                    'attr' => array('class' => 'drop'),
                )
            )
            ->add(
                'tag',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.update.form.tag', 'mapped' => false, 'required' => false)
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
                    'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr) {
                        return $dr->getFormFiltered();
                    },
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'tags',
                DocumentType::class,
                array(
                    'label' => $options['translation_route'].'.admin.update.form.tags',
                    'class' => 'FhmMediaBundle:MediaTag',
                    'choice_label' => 'route',
                    'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr) {
                        return $dr->getFormFiltered();
                    },
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
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