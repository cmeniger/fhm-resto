<?php
namespace Fhm\NewsletterBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('newsletter');
        parent::buildForm($builder, $options);
        $builder
            ->add('email', EmailType::class, array('label' => $this->translation . '.admin.update.form.email'))
            ->remove('name')
            ->remove('description')
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages');
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