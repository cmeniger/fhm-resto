<?php
namespace Fhm\CardBundle\Form\Type\Api\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateType extends AbstractType
{
    protected $instance;
    protected $document;
    protected $card;

    public function __construct($instance, $document, $card)
    {
        $this->instance = $instance;
        $this->document = $document;
        $this->card     = $card;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => $this->instance->translation . '.api.create.form.name'))
            ->add('description', 'textarea', array('label' => $this->instance->translation . '.api.create.form.description', 'required' => false))
            ->add('price', 'money', array('label' => $this->instance->translation . '.api.create.form.price', 'currency' => '', 'required' => false))
            ->add('currency', 'text', array('label' => $this->instance->translation . '.api.create.form.currency', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.api.create.form.order', 'required' => false))
            ->add('forward', 'checkbox', array('label' => $this->instance->translation . '.api.create.form.forward', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.api.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('categories', 'document', array(
                'label'         => $this->instance->translation . '.api.create.form.categories',
                'class'         => 'FhmCardBundle:CardCategory',
                'property'      => 'route',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardCategoryRepository $dr)
                {
                    return $dr->setSort('route')->getFormCard($this->card, $this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('ingredients', 'document', array(
                'label'         => $this->instance->translation . '.api.create.form.ingredients',
                'class'         => 'FhmCardBundle:CardIngredient',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardIngredientRepository $dr)
                {
                    return $dr->getFormCard($this->card, $this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('submitSave', 'submit', array('label' => $this->instance->translation . '.api.create.form.submit.save'));
    }

    public function getName()
    {
        return 'FhmCreate';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults
        (
            array(
                'data_class'         => $this->instance->class,
                'translation_domain' => $this->instance->domain,
                'cascade_validation' => true
            )
        );
    }
}