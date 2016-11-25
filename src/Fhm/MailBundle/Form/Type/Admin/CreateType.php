<?php
namespace Fhm\MailBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('mail');
        $builder
            ->add('to', EmailType::class, array('label' => $this->instance->translation . '.admin.create.form.to', 'mapped' => false))
            ->add('object', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.object', 'mapped' => false))
            ->add('body', TextareaType::class, array('label' => $this->instance->translation . '.admin.create.form.body', 'attr' => array('class' => 'editor'), 'mapped' => false))
            ->add('submitSave', SubmitType::class, array('label' => $this->instance->translation . '.admin.create.form.submit.save'));
    }
}