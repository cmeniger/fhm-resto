<?php
namespace Fhm\NewsletterBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InscriptionType
 * @package Fhm\NewsletterBundle\Form\Type\Front
 */
class InscriptionType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('newsletter');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'submitQuit',
                SubmitType::class,
                array('label' => $options['translation_route'].'.front.create.form.submit.inscription')
            )
            ->add('email', EmailType::class, array('label' => $options['translation_route'].'.admin.create.form.email'))
            ->remove('name')
            ->remove('description')
            ->remove('submitSave')
            ->remove('submitDuplicate')
            ->remove('submitNew');
    }

}