<?php
namespace Fhm\CardBundle\Form\Type\Admin\Product;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('price', 'money', array('label' => $this->instance->translation . '.admin.create.form.price', 'currency' => '', 'required' => false))
            ->add('currency', 'text', array('label' => $this->instance->translation . '.admin.create.form.currency', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('forward', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.forward', 'required' => false))
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
            ->add('categories', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.categories',
                'class'         => 'FhmCardBundle:CardCategory',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardCategoryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('ingredients', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.ingredients',
                'class'         => 'FhmCardBundle:CardIngredient',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardIngredientRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ));
    }
}