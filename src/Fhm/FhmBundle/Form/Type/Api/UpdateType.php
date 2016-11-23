<?php

namespace Fhm\FhmBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('name', TextType::class, array('label' => $this->instance->translation . '.front.update.form.name'))
            ->add('description', TextareaType::class, array('label' => $this->instance->translation . '.front.update.form.description', 'required' => false))
            ->add('submitSave', SubmitType::class, array('label' => $this->instance->translation . '.front.update.form.submit.save'))
            ->add('submitNew', SubmitType::class, array('label' => $this->instance->translation . '.front.update.form.submit.new'))
            ->add('submitDuplicate',SubmitType::class, array('label' => $this->instance->translation . '.front.update.form.submit.duplicate'))
            ->add('submitQuit', SubmitType::class, array('label' => $this->instance->translation . '.front.update.form.submit.quit'));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmUpdate';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => $this->instance->class,
            'translation_domain' => $this->instance->domain,
            'cascade_validation' => true
        ));
    }
}