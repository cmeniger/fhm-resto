<?php
namespace Fhm\GalleryBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.title'))
            ->add('subtitle', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.subtitle', 'required' => false))
            ->add('resume', TextareaType::class, array('label' => $this->instance->translation . '.admin.create.form.resume', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('content', TextareaType::class, array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('add_global_item', CheckboxType::class, array('label' => $this->instance->translation . '.admin.create.form.add_global_item', 'required' => false))
            ->add('add_global_video', CheckboxType::class, array('label' => $this->instance->translation . '.admin.create.form.add_global_video', 'required' => false))
            ->add('order_item', ChoiceType::class, array('label' => $this->instance->translation . '.admin.create.form.order_item', 'choices' => $this->_sortChoices()))
            ->add('order_video', ChoiceType::class, array('label' => $this->instance->translation . '.admin.create.form.order_video', 'choices' => $this->_sortChoices()))
            ->add('order', IntegerType::class, array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('image', MediaType::class, array(
                'label'    => $this->instance->translation . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('albums', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.albums',
                'class'         => 'FhmGalleryBundle:GalleryAlbum',
                'property'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryAlbumRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->add('items', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.items',
                'class'         => 'FhmGalleryBundle:GalleryItem',
                'property'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryItemRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->add('videos', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.videos',
                'class'         => 'FhmGalleryBundle:GalleryVideo',
                'property'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryVideoRepository $dr)
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

    private function _sortChoices()
    {
        return array
        (
            "title"            => $this->instance->translation . '.admin.sort.title.asc',
            "title desc"       => $this->instance->translation . '.admin.sort.title.desc',
            "order"            => $this->instance->translation . '.admin.sort.order.asc',
            "order desc"       => $this->instance->translation . '.admin.sort.order.desc',
            "date_create"      => $this->instance->translation . '.admin.sort.create.asc',
            "date_create desc" => $this->instance->translation . '.admin.sort.create.desc',
            "date_update"      => $this->instance->translation . '.admin.sort.update.asc',
            "date_update desc" => $this->instance->translation . '.admin.sort.update.desc'
        );
    }
}