<?php
namespace Fhm\GalleryBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\GalleryBundle\Form\Type\Admin
 */
class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('gallery');
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->translation . '.admin.update.form.title'))
            ->add('subtitle', TextType::class, array('label' => $this->translation . '.admin.update.form.subtitle', 'required' => false))
            ->add('resume', TextareaType::class, array('label' => $this->translation . '.admin.update.form.resume', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('content', TextareaType::class, array('label' => $this->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('add_global_item', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.add_global_item', 'required' => false))
            ->add('add_global_video', CheckboxType::class, array('label' => $this->translation . '.admin.update.form.add_global_video', 'required' => false))
            ->add('order_item', ChoiceType::class, array('label' => $this->translation . '.admin.update.form.order_item', 'choices' => $this->_sortChoices()))
            ->add('order_video', ChoiceType::class, array('label' => $this->translation . '.admin.update.form.order_video', 'choices' => $this->_sortChoices()))
            ->add('order', IntegerType::class, array('label' => $this->translation . '.admin.update.form.order', 'required' => false))
            ->add('image', MediaType::class, array(
                'label'    => $this->translation . '.admin.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('albums', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.albums',
                'class'         => 'FhmGalleryBundle:GalleryAlbum',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryAlbumRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->add('items', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.items',
                'class'         => 'FhmGalleryBundle:GalleryItem',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryItemRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->add('videos', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.videos',
                'class'         => 'FhmGalleryBundle:GalleryVideo',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryVideoRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->remove('name')
            ->remove('description');
    }

    /**
     * @return array
     */
    private function _sortChoices()
    {
        return array
        (
            "title"            => $this->translation . '.admin.sort.title.asc',
            "title desc"       => $this->translation . '.admin.sort.title.desc',
            "order"            => $this->translation . '.admin.sort.order.asc',
            "order desc"       => $this->translation . '.admin.sort.order.desc',
            "date_update"      => $this->translation . '.admin.sort.update.asc',
            "date_update desc" => $this->translation . '.admin.sort.update.desc',
            "date_update"      => $this->translation . '.admin.sort.update.asc',
            "date_update desc" => $this->translation . '.admin.sort.update.desc'
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\GalleryBundle\Document\Gallery',
                'translation_domain' => 'FhmGalleryBundle',
                'cascade_validation' => true,
            )
        );
    }
}