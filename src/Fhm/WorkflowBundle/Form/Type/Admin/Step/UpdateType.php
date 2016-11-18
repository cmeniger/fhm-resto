<?php
namespace Fhm\WorkflowBundle\Form\Type\Admin\Step;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('color', 'text', array('label' => $this->instance->translation . '.admin.update.form.color', 'attr' => array('class' => 'colorpicker'), 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global');
    }
}