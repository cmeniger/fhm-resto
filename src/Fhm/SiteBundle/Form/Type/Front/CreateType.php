<?php
namespace Fhm\SiteBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CreateType
 *
 * @package Fhm\SiteBundle\Form\Type\Front
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('site');
        parent::buildForm($builder, $options);
    }
}