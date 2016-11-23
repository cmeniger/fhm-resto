<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Item;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
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
            ->add('content', TextareaType::class, array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('link', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.link', 'required' => false))
            ->add('order', IntegerType::class, array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('image', MediaType::class, array(
                'label'  => $this->instance->translation . '.admin.create.form.image',
                'filter' => 'image/*'
            ))
            ->add('galleries', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.galleries',
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