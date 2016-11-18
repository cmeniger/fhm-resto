<?php
namespace Core\GeolocationBundle\Form\Type\Admin;

use Core\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('address_search', 'text', array('label' => 'label.address_search', 'mapped' => false, 'required' => false))
            ->add('address_main', 'text', array('label' => 'label.address_main', 'required' => false))
            ->add('address_additional', 'text', array('label' => 'label.address_additional', 'required' => false))
            ->add('zip_code', 'text', array('label' => 'label.zip_code', 'required' => false))
            ->add('city', 'text', array('label' => 'label.city', 'required' => false))
            ->add('country', 'text', array('label' => 'label.country', 'required' => false))
            ->add('latitude', 'hidden', array('label' => 'label.latitude', 'required' => false))
            ->add('longitude', 'hidden', array('label' => 'label.longitude', 'required' => false));
    }
}