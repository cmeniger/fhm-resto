<?php
namespace Fhm\CardBundle\Form\Type\Api\Ingredient;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\CardBundle\Form\Type\Api\Ingredient
 */
class UpdateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['translation_route'] = 'card.ingredient';
        $builder->add(
            'name',
            TextType::class,
            array('label' => $options['translation_route'].'.api.update.form.name')
        )->add(
            'description',
            TextareaType::class,
            array('label' => $options['translation_route'].'.api.update.form.description', 'required' => false)
        )->add(
            'order',
            IntegerType::class,
            array('label' => $options['translation_route'].'.api.update.form.order', 'required' => false)
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $this->instance->translation.'.api.update.form.image',
                'filter' => 'image/*',
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
            array('label' => $options['translation_route'].'.api.update.form.submit.save')
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
                'data_class' => 'Fhm\FhmCardBundle\Document\CardIngredient',
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
                'translation_route' => 'card.ingredient',
            )
        );
    }
}