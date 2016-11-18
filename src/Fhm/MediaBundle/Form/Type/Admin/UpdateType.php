<?php
namespace Fhm\MediaBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('file', 'file', array('label' => $this->instance->translation . '.admin.update.form.file', 'required' => false, 'attr'=>array('class'=>'drop')))
            ->add('tag', 'text', array('label' => $this->instance->translation . '.admin.update.form.tag', 'mapped' => false, 'required' => false))
            ->add('private', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.private', 'required' => false))
            ->add('parent', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.parent',
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
                'label'         => $this->instance->translation . '.admin.update.form.tags',
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
            ->remove('global');
    }
}