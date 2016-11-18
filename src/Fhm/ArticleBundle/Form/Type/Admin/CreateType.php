<?php
namespace Fhm\ArticleBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', 'text', array('label' => $this->instance->translation . '.admin.create.form.title'))
            ->add('subtitle', 'text', array('label' => $this->instance->translation . '.admin.create.form.subtitle', 'required' => false))
            ->add('resume', 'textarea', array('label' => $this->instance->translation . '.admin.create.form.resume', 'attr' => array('class' => 'editor')))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor')))
            ->add('image', 'media', array(
                    'label'    => $this->instance->translation . '.admin.create.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                )
            )
            ->add('gallery', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.gallery',
                'class'         => 'FhmGalleryBundle:Gallery',
                'property'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('author', 'autocomplete', array(
                'label'    => $this->instance->translation . '.admin.create.form.author',
                'class'    => 'FhmUserBundle:User',
                'url'      => 'fhm_api_user_autocomplete',
                'required' => false
            ))
            ->remove('global')
            ->remove('name')
            ->remove('description');
    }
}