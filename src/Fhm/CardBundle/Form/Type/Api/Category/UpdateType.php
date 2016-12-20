<?php
namespace Fhm\CardBundle\Form\Type\Api\Category;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
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
 * Class UpdateType
 *
 * @package Fhm\CardBundle\Form\Type\Api\Category
 */
class UpdateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['translation_route'] = 'card.category';
        $builder->add(
            'name',
            TextType::class,
            array('label' => $options['translation_route'].'.api.update.form.name')
        )->add(
            'description',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.description',
                'required' => false,
            )
        )->add(
            'price',
            MoneyType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.price',
                'currency' => '',
                'required' => false,
            )
        )->add(
            'currency',
            TextType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.currency',
                'required' => false,
            )
        )->add(
            'order',
            IntegerType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.order',
                'required' => false,
            )
        )->add(
            'menu',
            CheckboxType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.menu',
                'required' => false,
            )
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.image',
                'filter' => 'image/*',
                'required' => false,
            )
        )->add(
            'parents',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.parents',
                'class' => 'FhmCardBundle:CardCategory',
                'choice_label' => 'route',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardCategoryRepository $dr) {
//                    return $dr->setSort('route')->getFormParents($this->card, $this->instance->grouping->filtered);
                },
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
            )
        )->add(
            'products',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.products',
                'class' => 'FhmCardBundle:CardProduct',
                'choice_label' => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardProductRepository $dr) {
//                    return $dr->setSort('alias')->getFormCard($this->card, $this->instance->grouping->filtered);
                },
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
            )
        )->add(
            'submitSave',
            SubmitType::class,
            array(
                'label' => $options['translation_route'].'.api.update.form.submit.save',
            )
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'FhmUpdate';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\FhmCardBundle\Document\CardCategory',
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
            )
        );
    }
}