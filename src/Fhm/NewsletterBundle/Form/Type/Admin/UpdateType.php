<?php
namespace Fhm\NewsletterBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('newsletter');
        parent::buildForm($builder, $options);
        $builder
            ->add('email', EmailType::class, array('label' => $this->instance->translation . '.admin.update.form.email'))
            ->remove('name')
            ->remove('description')
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages');
    }
}