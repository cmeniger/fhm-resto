<?php
namespace Fhm\GalleryBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', 'text', array('label' => $this->instance->translation . '.admin.update.form.title'))
            ->add('subtitle', 'text', array('label' => $this->instance->translation . '.admin.update.form.subtitle', 'required' => false))
            ->add('resume', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.resume', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('add_global_item', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.add_global_item', 'required' => false))
            ->add('add_global_video', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.add_global_video', 'required' => false))
            ->add('order_item', 'choice', array('label' => $this->instance->translation . '.admin.create.form.order_item', 'choices' => $this->_sortChoices()))
            ->add('order_video', 'choice', array('label' => $this->instance->translation . '.admin.create.form.order_video', 'choices' => $this->_sortChoices()))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.admin.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('albums', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.albums',
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
            ->add('items', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.items',
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
            ->add('videos', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.videos',
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