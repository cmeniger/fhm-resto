<?php
namespace Fhm\WorkflowBundle\Form\Type\Front\Step;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('workflow');
        parent::buildForm($builder, $options);
    }
}