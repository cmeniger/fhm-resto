<?php
namespace Fhm\CardBundle\Form\Type\Admin\Category;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\CardBundle\Repository\CardCategoryRepository;
use Fhm\CardBundle\Repository\CardProductRepository;
use Fhm\CardBundle\Repository\CardRepository;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\CardBundle\Form\Type\Admin\Category
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('price', MoneyType::class, array(
                'label'    => $options['translation_route'] . '.admin.create.form.price', 
                'currency' => '', 'required' => false
            ))
            ->add('currency', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.currency',
                'required' => false
            ))
            ->add('order', IntegerType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.order', 
                'required' => false
            ))
            ->add('menu', CheckboxType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.menu', 
                'required' => false
            ))
            ->add('default', CheckboxType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.default', 
                'required' => false
            ))
            ->add('image', MediaType::class, array(
                'label'    => $options['translation_route'] . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('card', DocumentType::class, array(
                'label'         => $options['translation_route'] . '.admin.create.form.card',
                'class'         => 'FhmCardBundle:Card',
                'choice_label'  => 'name',
                'query_builder' => function (CardRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required'      => false
            ))
            ->add('products', DocumentType::class, array(
                'label'         => $options['translation_route'] . '.admin.create.form.products',
                'class'         => 'FhmCardBundle:CardProduct',
                'choice_label'  => 'name',
                'query_builder' => function (CardProductRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('parents', DocumentType::class, array(
                'label'         => $options['translation_route'] . '.admin.create.form.parents',
                'class'         => 'FhmCardBundle:CardCategory',
                'choice_label'  => 'name',
                'query_builder' => function (CardCategoryRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('sons', DocumentType::class, array(
                'label'         => $options['translation_route'] . '.admin.create.form.sons',
                'class'         => 'FhmCardBundle:CardCategory',
                'choice_label'  => 'name',
                'query_builder' => function (CardCategoryRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ));
    }
}