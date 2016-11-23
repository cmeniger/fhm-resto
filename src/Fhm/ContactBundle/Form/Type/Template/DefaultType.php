<?php
namespace Fhm\ContactBundle\Form\Type\Template;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('firstname', TextType::class, array('label' => $this->instance->translation . '.front.form.firstname', 'mapped' => false))
            ->add('lastname', TextType::class, array('label' => $this->instance->translation . '.front.form.lastname', 'mapped' => false))
            ->add('email', EmailType::class, array('label' => $this->instance->translation . '.front.form.email', 'mapped' => false))
            ->add('phone', TextType::class, array('label' => $this->instance->translation . '.front.form.phone', 'mapped' => false, 'required' => false))
            ->add('content', TextareaType::class, array('label' => $this->instance->translation . '.front.form.content', 'mapped' => false))
            ->add('submit', SubmitType::class, array('label' => $this->instance->translation . '.front.form.submit'));
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