<?php
namespace Fhm\MediaBundle\Form\Type\Admin\Tag;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('color', 'text', array('label' => $this->instance->translation . '.admin.update.form.color', 'attr' => array('class' => 'colorpicker'), 'required' => false))
            ->add('private', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.private', 'required' => false))
            ->add('parent', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.parent',
                'class'         => 'FhmMediaBundle:MediaTag',
                'property'      => 'route',
                'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                    {
                        return $dr->getFormFiltered($this->instance->grouping->filtered);
                    },
                'required'      => false
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