<?php
namespace Fhm\WorkflowBundle\Form\Type\Front\Log;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UpdateType
 * @package Fhm\WorkflowBundle\Form\Type\Front\Log
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}