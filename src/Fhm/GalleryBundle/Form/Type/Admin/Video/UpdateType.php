<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Video;

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
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('link', 'text', array('label' => $this->instance->translation . '.admin.update.form.link', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->add('video', 'url', array('label' => $this->instance->translation . '.admin.update.form.video'))
            ->add('galleries', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.galleries',
                'class'         => 'FhmGalleryBundle:Gallery',
                'property'      => 'name',
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
}