<?php
namespace Fhm\PartnerBundle\Form\Type\Front\Group;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('partner');
        parent::buildForm($builder, $options);
    }
}