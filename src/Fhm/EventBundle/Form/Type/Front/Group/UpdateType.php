<?php
namespace Fhm\EventBundle\Form\Type\Front\Group;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/***
 * Class UpdateType
 * @package Fhm\EventBundle\Form\Type\Front\Group
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('event');
        parent::buildForm($builder, $options);
    }
}