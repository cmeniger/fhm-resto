<?php
namespace Fhm\MediaBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name', 'text', array('label' => $this->instance->translation . '.admin.create.form.name', 'required' => false))
            ->add('file', 'file', array('label' => $this->instance->translation . '.admin.create.form.file'))
            ->add('tag', 'text', array('label' => $this->instance->translation . '.admin.create.form.tag', 'mapped' => false, 'required' => false))
            ->add('private', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.private', 'required' => false))
            ->add('parent', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.parent',
                'class'         => 'FhmMediaBundle:MediaTag',
                'property'      => 'route',
                'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->getFormFiltered($this->instance->grouping->filtered);
                },
                'mapped'        => false,
                'required'      => false
            ))
            ->add('tags', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.tags',
                'class'         => 'FhmMediaBundle:MediaTag',
                'property'      => 'route',
                'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->getFormFiltered($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit')
            ->remove('submitConfig');
    }
}