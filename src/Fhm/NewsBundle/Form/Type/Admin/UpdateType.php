<?php
namespace Fhm\NewsBundle\Form\Type\Admin;

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
            ->add('resume', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.resume', 'attr' => array('class' => 'editor')))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor')))
            ->add('date_start', 'datetime', array('label' => $this->instance->translation . '.admin.update.form.start', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker'), 'required' => false))
            ->add('date_end', 'datetime', array('label' => $this->instance->translation . '.admin.update.form.end', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker'), 'required' => false))
            ->add('image', 'media', array(
                    'label'    => $this->instance->translation . '.admin.update.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                )
            )
            ->add('gallery', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.gallery',
                'class'         => 'FhmGalleryBundle:Gallery',
                'property'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('newsgroups', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.newsgroups',
                'class'         => 'FhmNewsBundle:NewsGroup',
                'property'      => 'name',
                'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('author', 'autocomplete', array(
                'label'    => $this->instance->translation . '.admin.update.form.author',
                'class'    => 'FhmUserBundle:User',
                'url'      => 'fhm_api_user_autocomplete',
                'required' => false
            ))
            ->remove('name')
            ->remove('description');
    }
}