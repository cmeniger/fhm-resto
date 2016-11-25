<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Item;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\GalleryBundle\Form\Type\Admin\Item
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('gallery.item');
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->ranslation . '.admin.create.form.title'))
            ->add('subtitle', TextType::class, array('label' => $this->translation . '.admin.create.form.subtitle', 'required' => false))
            ->add('content', TextareaType::class, array('label' => $this->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('link', TextType::class, array('label' => $this->translation . '.admin.create.form.link', 'required' => false))
            ->add('order', IntegerType::class, array('label' => $this->translation . '.admin.create.form.order', 'required' => false))
            ->add('image', MediaType::class, array(
                'label'  => $this->instance->translation . '.admin.create.form.image',
                'filter' => 'image/*'
            ))
            ->add('galleries', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.galleries',
                'class'         => 'FhmGalleryBundle:Gallery',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->remove('name')
            ->remove('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\GalleryBundle\Document\GalleryItem',
                'translation_domain' => 'FhmGalleryBundle',
                'cascade_validation' => true,
            )
        );
    }
}