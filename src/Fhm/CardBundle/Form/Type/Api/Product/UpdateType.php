<?php
namespace Fhm\CardBundle\Form\Type\Api\Product;

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
 * @package Fhm\CardBundle\Form\Type\Api\Product
 */
class UpdateType extends AbstractType
{
    protected $instance;
    protected $document;
    protected $card;
    protected $translation;

    public function __construct($instance, $document, $card)
    {
        $this->instance = $instance;
        $this->document = $document;
        $this->card     = $card;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->translation='card.product';
        $builder
            ->add('name', TextType::class, array('label' => $this->translation . '.api.update.form.name'))
            ->add('description', TextareaType::class, array('label' => $this->translation . '.api.update.form.description', 'required' => false))
            ->add('price', MoneyType::class, array('label' => $this->translation . '.api.update.form.price', 'currency' => '', 'required' => false))
            ->add('currency', TextType::class, array('label' => $this->translation . '.api.update.form.currency', 'required' => false))
            ->add('order', IntegerType::class, array('label' => $this->translation . '.api.update.form.order', 'required' => false))
            ->add('forward', CheckboxType::class, array('label' => $this->translation . '.api.update.form.forward', 'required' => false))
            ->add('image', MediaType::class, array(
                'label'    => $this->translation . '.api.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('categories', DocumentType::class, array(
                'label'         => $this->translation . '.api.update.form.categories',
                'class'         => 'FhmCardBundle:CardCategory',
                'choice_label'      => 'route',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardCategoryRepository $dr)
                {
                    //                    return $dr->setSort('route')->getFormCard($this->card, $this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('ingredients', DocumentType::class, array(
                'label'         => $this->translation . '.api.update.form.ingredients',
                'class'         => 'FhmCardBundle:CardIngredient',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardIngredientRepository $dr)
                {
                    //                    return $dr->getFormCard($this->card, $this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('submitSave', SubmitType::class, array('label' => $this->translation . '.api.update.form.submit.save'));
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
                'data_class' => 'Fhm\FhmCardBundle\Document\CardProduct',
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
            )
        );
    }
}