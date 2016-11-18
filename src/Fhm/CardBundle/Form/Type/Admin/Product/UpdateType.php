<?php
namespace Fhm\CardBundle\Form\Type\Admin\Product;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('price', 'money', array('label' => $this->instance->translation . '.admin.update.form.price', 'currency' => '', 'required' => false))
            ->add('currency', 'text', array('label' => $this->instance->translation . '.admin.update.form.currency', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->add('forward', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.forward', 'required' => false))
            ->add('default', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.default', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.admin.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('card', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.card',
                'class'         => 'FhmCardBundle:Card',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('categories', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.categories',
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
                'label'         => $this->instance->translation . '.admin.update.form.ingredients',
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