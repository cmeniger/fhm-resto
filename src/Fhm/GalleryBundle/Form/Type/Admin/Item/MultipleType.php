<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Item;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class MultipleType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', 'text', array('label' => $this->instance->translation . '.admin.multiple.form.title'))
            ->add('subtitle', 'text', array('label' => $this->instance->translation . '.admin.multiple.form.subtitle', 'required' => false))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.multiple.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('link', 'text', array('label' => $this->instance->translation . '.admin.multiple.form.link', 'required' => false))
            ->add('galleries', 'document', array(
                'label'         => $this->instance->translation . '.admin.multiple.form.galleries',
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
            ->add('file', 'file', array('label' => $this->instance->translation . '.admin.multiple.form.file', 'mapped' => false))
            ->add('tag', 'text', array('label' => $this->instance->translation . '.admin.multiple.form.tag', 'required' => false, 'mapped' => false))
            ->add('parent', 'document', array(
                'label'              => $this->instance->translation . '.admin.multiple.form.parent',
                'class'              => 'FhmMediaBundle:MediaTag',
                'property'           => 'route',
                'query_builder'      => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'required'           => false,
                'mapped'             => false
            ))
            ->add('tags', 'document', array(
                'label'              => $this->instance->translation . '.admin.multiple.form.tags',
                'class'              => 'FhmMediaBundle:MediaTag',
                'property'           => 'route',
                'query_builder'      => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'           => true,
                'required'           => false,
                'by_reference'       => false,
                'mapped'             => false
            ))
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit')
            ->remove('submitConfig')
            ->remove('name')
            ->remove('description');
    }
}