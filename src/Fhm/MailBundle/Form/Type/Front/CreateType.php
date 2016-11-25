<?php
namespace Fhm\MailBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('mail');
        parent::buildForm($builder, $options);
    }
}