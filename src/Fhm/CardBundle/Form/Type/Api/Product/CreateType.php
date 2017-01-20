<?php
namespace Fhm\CardBundle\Form\Type\Api\Product;

use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 *
 * @package Fhm\CardBundle\Form\Type\Api\Product
 */
class CreateType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['translation_route'] = 'card.product';
        $builder->add(
            'name',
            TextType::class,
            array('label' => $options['translation_route'].'.api.create.form.name')
        )->add(
            'description',
            TextareaType::class,
            array('label' => $options['translation_route'].'.api.create.form.description', 'required' => false)
        )->add(
            'price',
            MoneyType::class,
            array(
                'label' => $options['translation_route'].'.api.create.form.price',
                'currency' => '',
                'required' => false,
            )
        )->add(
            'currency',
            TextType::class,
            array('label' => $options['translation_route'].'.api.create.form.currency', 'required' => false)
        )->add(
            'order',
            IntegerType::class,
            array('label' => $options['translation_route'].'.api.create.form.order', 'required' => false)
        )->add(
            'forward',
            CheckboxType::class,
            array('label' => $options['translation_route'].'.api.create.form.forward', 'required' => false)
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.api.create.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'categories',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.api.create.form.categories',
                'class' => 'FhmCardBundle:CardCategory',
                'choice_label' => 'route',
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
            )
        )->add(
            'ingredients',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.api.create.form.ingredients',
                'class' => 'FhmCardBundle:CardIngredient',
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            )
        )->add(
            'submitSave',
            SubmitType::class,
            array('label' => $options['translation_route'].'.api.create.form.submit.save')
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmCreate';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
                'translation_route' => 'card.product',
                'object_manager'=>'',
                'user_admin' => '',
            )
        );
    }
}