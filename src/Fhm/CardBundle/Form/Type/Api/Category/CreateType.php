<?php
namespace Fhm\CardBundle\Form\Type\Api\Category;

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
            ->add('menu', 'checkbox', array('label' => $this->instance->translation . '.api.create.form.menu', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.api.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('parents', 'document', array(
                'label'         => $this->instance->translation . '.api.create.form.parents',
                'class'         => 'FhmCardBundle:CardCategory',
                'choice_label'      => 'route',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardCategoryRepository $dr)
                {
                    return $dr->setSort('route')->getFormParents($this->card, $this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('products', 'document', array(
                'label'         => $this->instance->translation . '.api.create.form.products',
                'class'         => 'FhmCardBundle:CardProduct',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardProductRepository $dr)
                {
                    return $dr->setSort('alias')->getFormCard($this->card, $this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
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