<?php
namespace Fhm\NewsletterBundle\Form\Type\Api;

use Fhm\FhmBundle\Form\Type\Api\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('email', 'email', array('label' => $this->instance->translation . '.admin.create.form.email'))
            ->remove('name')
            ->remove('description')
            ->remove('submitSave')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit');
    }
}