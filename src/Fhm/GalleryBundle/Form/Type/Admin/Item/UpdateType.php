<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Item;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->instance->translation . '.admin.update.form.title'))
            ->add('subtitle', TextType::class, array('label' => $this->instance->translation . '.admin.update.form.subtitle', 'required' => false))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('link', TextType::class, array('label' => $this->instance->translation . '.admin.update.form.link', 'required' => false))
            ->add('order', IntegerType::class, array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->add('image', MediaType::class, array(
                'label'  => $this->instance->translation . '.admin.update.form.image',
                'filter' => 'image/*'
            ))
            ->add('galleries', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.update.form.galleries',
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
}