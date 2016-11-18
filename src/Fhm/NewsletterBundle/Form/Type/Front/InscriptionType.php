<?php
namespace Fhm\NewsletterBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class InscriptionType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('submitQuit', 'submit', array('label' => $this->instance->translation . '.front.create.form.submit.inscription'))
            ->add('email', 'email', array('label' => $this->instance->translation . '.admin.create.form.email'))
            ->remove('name')
            ->remove('description')
            ->remove('submitSave')
            ->remove('submitDuplicate')
            ->remove('submitNew');
    }
}