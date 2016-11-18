<?php
namespace Fhm\ContactBundle\Form\Type\Template;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DefaultType extends AbstractType
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('recaptcha', 'ewz_recaptcha', array(
//                'label'       => $this->instance->translation . '.front.form.recaptcha',
//                'attr'        => array(
//                    'options' => array(
//                        'theme' => 'clean'
//                    )
//                ),
//                'constraints' => array(
//                    new RecaptchaTrue()
//                ),
//                'mapped'      => false
//            ))
            ->add('firstname', 'text', array('label' => $this->instance->translation . '.front.form.firstname', 'mapped' => false))
            ->add('lastname', 'text', array('label' => $this->instance->translation . '.front.form.lastname', 'mapped' => false))
            ->add('email', 'email', array('label' => $this->instance->translation . '.front.form.email', 'mapped' => false))
            ->add('phone', 'text', array('label' => $this->instance->translation . '.front.form.phone', 'mapped' => false, 'required' => false))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.front.form.content', 'mapped' => false))
            ->add('submit', 'submit', array('label' => $this->instance->translation . '.front.form.submit'));
    }

    public function getName()
    {
        return 'FhmContactDefault';
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