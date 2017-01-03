<?php
namespace Fhm\CardBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\CardBundle\Repository\CardCategoryRepository;
use Fhm\CardBundle\Repository\CardIngredientRepository;
use Fhm\CardBundle\Repository\CardProductRepository;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\CardBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'categories',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.categories',
                'class' => 'FhmCardBundle:CardCategory',
                'choice_label' => 'name',
                'query_builder' => function (CardCategoryRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
            )
        )->add(
            'products',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.products',
                'class' => 'FhmCardBundle:CardProduct',
                'choice_label' => 'name',
                'query_builder' => function (CardProductRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
            )
        )->add(
            'ingredients',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.ingredients',
                'class' => 'FhmCardBundle:CardIngredient',
                'choice_label' => 'name',
                'query_builder' => function (CardIngredientRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
            )
        );
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\CardBundle\Document\Card',
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
                'translation_route' => 'card',
                'filter' => '',
                'user_admin' => '',
            )
        );
    }
}