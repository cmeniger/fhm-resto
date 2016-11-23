<?php
namespace Fhm\CardBundle\Form\Type\Admin\Category;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('price', MoneyType::class, array('label' => $this->instance->translation . '.admin.update.form.price', 'currency' => '', 'required' => false))
            ->add('currency', TextType::class, array('label' => $this->instance->translation . '.admin.update.form.currency', 'required' => false))
            ->add('order', IntegerType::class, array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->add('menu', CheckboxType::class, array('label' => $this->instance->translation . '.admin.update.form.menu', 'required' => false))
            ->add('default', CheckboxType::class, array('label' => $this->instance->translation . '.admin.update.form.default', 'required' => false))
            ->add('image', MediaType::class, array(
                'label'    => $this->instance->translation . '.admin.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('card', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.update.form.card',
                'class'         => 'FhmCardBundle:Card',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('products', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.update.form.products',
                'class'         => 'FhmCardBundle:CardProduct',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardProductRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('parents', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.update.form.parents',
                'class'         => 'FhmCardBundle:CardCategory',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardCategoryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('sons', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.update.form.sons',
                'class'         => 'FhmCardBundle:CardCategory',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardCategoryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ));
    }

}