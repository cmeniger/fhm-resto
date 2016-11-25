<?php
namespace Fhm\EventBundle\Form\Type\Front\Group;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('event');
        parent::buildForm($builder, $options);
    }
}