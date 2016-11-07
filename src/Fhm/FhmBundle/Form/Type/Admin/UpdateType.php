<?php

namespace Fhm\FhmBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateType extends AbstractType
{
    protected $instance;
    protected $document;

    public function __construct($instance, $document)
    {
        $this->instance = $instance;
        $this->document = $document;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $this->instance->translation . '.admin.update.form.name'))
            ->add('description', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.description', 'required' => false))
            ->add('seo_title', 'text', array('label' => 'fhm.seo.title', 'translation_domain' => 'FhmFhmBundle', 'required' => false))
            ->add('seo_description', 'text', array('label' => 'fhm.seo.description', 'translation_domain' => 'FhmFhmBundle', 'required' => false))
            ->add('seo_keywords', 'text', array('label' => 'fhm.seo.keywords', 'translation_domain' => 'FhmFhmBundle', 'required' => false))
            ->add('active', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.active', 'required' => false))
            ->add('submitSave', 'submit', array('label' => $this->instance->translation . '.admin.update.form.submit.save'))
            ->add('submitNew', 'submit', array('label' => $this->instance->translation . '.admin.update.form.submit.new'))
            ->add('submitDuplicate', 'submit', array('label' => $this->instance->translation . '.admin.update.form.submit.duplicate'))
            ->add('submitQuit', 'submit', array('label' => $this->instance->translation . '.admin.update.form.submit.quit'))
            ->add('submitConfig', 'submit', array('label' => $this->instance->translation . '.admin.update.form.submit.config'));
        if($this->instance->language->visible)
        {
            $builder->add('languages', 'choice', array(
                'choices'  => $this->instance->language->available,
                'multiple' => true,
            ));
        }
        if($this->instance->grouping->visible)
        {
            $builder
                ->add('grouping', 'choice', array(
                    'choices'  => $this->instance->grouping->available,
                    'multiple' => true,
                ))
                ->add('share', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.share', 'required' => false));
            if($this->instance->user->admin)
            {
                $builder->add('global', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.global', 'required' => false));
            }
        }
    }

    public function getName()
    {
        return 'FhmUpdate';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults
        (
            array(
                'data_class'         => $this->instance->class,
                'translation_domain' => $this->instance->domain,
                'cascade_validation' => true
            )
        );
    }
}