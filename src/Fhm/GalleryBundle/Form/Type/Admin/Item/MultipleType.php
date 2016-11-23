<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Item;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MultipleType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->instance->translation . '.admin.multiple.form.title'))
            ->add('subtitle', TextType::class, array('label' => $this->instance->translation . '.admin.multiple.form.subtitle', 'required' => false))
            ->add('content',TextareaType::class, array('label' => $this->instance->translation . '.admin.multiple.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('link', TextType::class, array('label' => $this->instance->translation . '.admin.multiple.form.link', 'required' => false))
            ->add('galleries', DocumentType::class, array(
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
            ->add('file', FileType::class, array('label' => $this->instance->translation . '.admin.multiple.form.file', 'mapped' => false))
            ->add('tag', TextType::class, array('label' => $this->instance->translation . '.admin.multiple.form.tag', 'required' => false, 'mapped' => false))
            ->add('parent',DocumentType::class, array(
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
            ->add('tags', DocumentType::class, array(
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