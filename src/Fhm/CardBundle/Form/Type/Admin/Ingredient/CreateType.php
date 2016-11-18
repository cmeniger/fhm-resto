<?php
namespace Fhm\CardBundle\Form\Type\Admin\Ingredient;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('default', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.default', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('card', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.card',
                'class'         => 'FhmCardBundle:Card',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('products', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.products',
                'class'         => 'FhmCardBundle:CardProduct',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardProductRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ));
    }
}