<?php
namespace Fhm\MediaBundle\Form\Type\Admin\Tag;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('media');
        parent::buildForm($builder, $options);
        $builder
            ->add('color', 'text', array('label' => $this->instance->translation . '.admin.create.form.color', 'attr' => array('class' => 'colorpicker'), 'required' => false))
            ->add('private', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.private', 'required' => false))
            ->add('parent', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.parent',
                'class'         => 'FhmMediaBundle:MediaTag',
                'choice_label'      => 'route',
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