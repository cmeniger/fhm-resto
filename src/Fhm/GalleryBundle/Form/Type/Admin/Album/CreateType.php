<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Album;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('add_global', CheckboxType::class, array('label' => $this->instance->translation . '.admin.create.form.add_global', 'required' => false))
            ->add('sort', ChoiceType::class, array('label' => $this->instance->translation . '.admin.create.form.sort', 'choices' => $this->_sortChoices()))
            ->add('image', MediaType::class, array(
                'label'    => $this->instance->translation . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
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