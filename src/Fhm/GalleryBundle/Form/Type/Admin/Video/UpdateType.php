<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Video;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\GalleryBundle\Repository\GalleryRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('gallery.video');
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.title'))
            ->add('subtitle', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.subtitle',
                'required' => false))
            ->add('content', TextareaType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.content',
                'attr' => array('class' => 'editor'), 'required' => false))
            ->add('link', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.link',
                'required' => false))
            ->add('order', IntegerType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.order',
                'required' => false))
            ->add('video', UrlType::class, array('label' => $options['translation_route'] . '.admin.update.form.video'))
            ->add('galleries', DocumentType::class, array(
                'label'         => $options['translation_route'] . '.admin.update.form.galleries',
                'class'         => 'FhmGalleryBundle:Gallery',
                'choice_label'      => 'name',
                'query_builder' => function (GalleryRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->remove('name')
            ->remove('description');
    }


}