<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Video;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\GalleryBundle\Repository\GalleryRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\GalleryBundle\Form\Type\Admin\Video
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $this->setTranslation('gallery.video');
        parent::buildForm($builder, $options);
        $builder->add(
            'title',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.title',
            )
        )->add(
            'subtitle',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.subtitle',
                'required' => false,
            )
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.content',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'link',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.link',
                'required' => false,
            )
        )->add(
            'order',
            IntegerType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.order',
                'required' => false,
            )
        )->add(
            'video',
            UrlType::class,
            array('label' => $options['translation_route'].'.admin.update.form.video')
        )->add(
            'galleries',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.update.form.galleries',
                'class' => 'FhmGalleryBundle:Gallery',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmGalleryBundle:Gallery');
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
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
                'data_class' => 'Fhm\GalleryBundle\Document\GalleryVideo',
                'translation_domain' => 'FhmGalleryBundle',
                'cascade_validation' => true,
                'translation_route' => 'gallery.video',
                'filter' => '',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }

}