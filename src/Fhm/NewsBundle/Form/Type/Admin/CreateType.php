<?php
namespace Fhm\NewsBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\AutocompleteType;
use Fhm\NewsBundle\Form\Type\Admin\Group\AddType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('resume', TextareaType::class, array('label' => $this->instance->translation . '.admin.create.form.resume', 'attr' => array('class' => 'editor')))
            ->add('content', TextareaType::class, array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor')))
            ->add('date_start', DateTimeType::class, array('label' => $this->instance->translation . '.admin.create.form.start', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker'), 'required' => false))
            ->add('date_end', DateTimeType::class, array('label' => $this->instance->translation . '.admin.create.form.end', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker'), 'required' => false))
            ->add('image', 'media', array(
                    'label'    => $this->instance->translation . '.admin.create.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                )
            )
            ->add('gallery',DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.gallery',
                'class'         => 'FhmGalleryBundle:Gallery',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('newsgroups',DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.newsgroups',
                'class'         => 'FhmNewsBundle:NewsGroup',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('author', AutocompleteType::class, array(
                'label'    => $this->instance->translation . '.admin.create.form.author',
                'class'    => 'FhmUserBundle:User',
                'url'      => 'fhm_api_user_autocomplete',
                'required' => false
            ))
            ->remove('name')
            ->remove('description');
    }
}