<?php
namespace Fhm\CardBundle\Form\Type\Admin\Product;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('price', MoneyType::class, array('label' => $this->instance->translation . '.admin.create.form.price', 'currency' => '', 'required' => false))
            ->add('currency', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.currency', 'required' => false))
            ->add('order', IntegerType::class, array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('forward', CheckboxType::class, array('label' => $this->instance->translation . '.admin.create.form.forward', 'required' => false))
            ->add('default', CheckboxType::class, array('label' => $this->instance->translation . '.admin.create.form.default', 'required' => false))
            ->add('image',MediaType::class, array(
                'label'    => $this->instance->translation . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('card', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.card',
                'class'         => 'FhmCardBundle:Card',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('categories',DocumentType::class, array(
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
            ->add('ingredients', DocumentType::class, array(
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