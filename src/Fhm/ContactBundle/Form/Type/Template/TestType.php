<?php
namespace Fhm\ContactBundle\Form\Type\Template;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TestType extends AbstractType
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array('label' => $this->instance->translation . '.form.firstname'))
            ->add('lastname', TextType::class, array('label' => $this->instance->translation . '.form.lastname'))
            ->add('email', EmailType::class, array('label' => $this->instance->translation . '.form.email'))
            ->add('phone',TextType::class, array('label' => $this->instance->translation . '.form.phone', 'required' => false))
            ->add('content', TextareaType::class, array('label' => $this->instance->translation . '.form.content'))
            ->add('submit', SubmitType::class, array('label' => $this->instance->translation . '.form.submit'));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmContactDefault';
    }

    public function configureOptions(OptionsResolver $resolver)
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