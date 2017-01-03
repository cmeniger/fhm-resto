<?php
namespace Fhm\NewsBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Form\Type\AutocompleteType;
use Fhm\GalleryBundle\Repository\GalleryRepository;
use Fhm\MediaBundle\Form\Type\MediaType;
use Fhm\NewsBundle\Repository\NewsGroupRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\NewsBundle\Form\Type\Admin
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
            'title',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.title')
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.subtitle', 'required' => false)
        )->add(
            'resume',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.resume',
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.content',
                'attr' => array('class' => 'editor'),
            )
        )->add(
            'date_start',
            DateTimeType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.start',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => array('class' => 'datetimepicker'),
                'required' => false,
            )
        )->add(
            'date_end',
            DateTimeType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.end',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => array('class' => 'datetimepicker'),
                'required' => false,
            )
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'gallery',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.gallery',
                'class' => 'FhmGalleryBundle:Gallery',
                'choice_label' => 'name',
                'query_builder' => function (GalleryRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'newsgroups',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.newsgroups',
                'class' => 'FhmNewsBundle:NewsGroup',
                'choice_label' => 'name',
                'query_builder' => function (NewsGroupRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->add(
            'author',
            AutocompleteType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.author',
                'class' => 'FhmUserBundle:User',
                'url' => 'fhm_api_user_autocomplete',
                'required' => false,
            )
        )->remove('name')->remove('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\NewsBundle\Document\News',
                'translation_domain' => 'FhmNewsBundle',
                'cascade_validation' => true,
                'translation_route' => 'news',
                'filter' => '',
                'user_admin' => '',
                'map' => '',
            )
        );
    }
}