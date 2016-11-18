<?php
namespace Fhm\MailBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('to', 'email', array('label' => $this->instance->translation . '.admin.create.form.to', 'mapped' => false))
            ->add('object', 'text', array('label' => $this->instance->translation . '.admin.create.form.object', 'mapped' => false))
            ->add('body', 'textarea', array('label' => $this->instance->translation . '.admin.create.form.body', 'attr' => array('class' => 'editor'), 'mapped' => false))
            ->add('submitSave', 'submit', array('label' => $this->instance->translation . '.admin.create.form.submit.save'));
    }
}