<?php
namespace Fhm\CardBundle\Form\Type\Admin\Ingredient;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\CardBundle\Repository\CardProductRepository;
use Fhm\CardBundle\Repository\CardRepository;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\CardBundle\Form\Type\Admin\Ingredient
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'order',
            IntegerType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.order',
                'required' => false,
            )
        )->add(
            'default',
            CheckboxType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.default',
                'required' => false,
            )
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'card',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.card',
                'class' => 'FhmCardBundle:Card',
                'choice_label' => 'name',
                'query_builder' => function (CardRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
            )
        )->add(
            'products',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.products',
                'class' => 'FhmCardBundle:CardProduct',
                'choice_label' => 'name',
                'query_builder' => function (CardProductRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
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
                'data_class' => 'Fhm\CardBundle\Document\CardIngredient',
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
                'translation_route' => 'card.ingredient',
                'filter' => '',
                'user_admin' => '',
            )
        );
    }

}