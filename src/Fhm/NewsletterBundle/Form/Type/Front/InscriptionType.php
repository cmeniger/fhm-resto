<?php
namespace Fhm\NewsletterBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('newsletter');
        parent::buildForm($builder, $options);
        $builder
            ->add('submitQuit', SubmitType::class, array('label' => $this->translation . '.front.create.form.submit.inscription'))
            ->add('email', EmailType::class, array('label' => $this->translation . '.admin.create.form.email'))
            ->remove('name')
            ->remove('description')
            ->remove('submitSave')
            ->remove('submitDuplicate')
            ->remove('submitNew');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\NewsletterBundle\Document\Newsletter',
                'translation_domain' => 'FhmNewsletterBundle',
                'cascade_validation' => true,
            )
        );
    }
}