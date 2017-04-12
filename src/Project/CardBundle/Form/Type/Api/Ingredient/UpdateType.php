<?php
namespace Fhm\CardBundle\Form\Type\Api\Ingredient;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateType extends AbstractType
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
            ->add('name', 'text', array('label' => $this->instance->translation . '.api.update.form.name'))
            ->add('description', 'textarea', array('label' => $this->instance->translation . '.api.update.form.description', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.api.update.form.order', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.api.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('products', 'document', array(
                'label'         => $this->instance->translation . '.api.update.form.products',
                'class'         => 'FhmCardBundle:CardProduct',
                'property'      => 'name',
                'query_builder' => function (\Fhm\CardBundle\Repository\CardProductRepository $dr)
                {
                    return $dr->setSort('alias')->getFormCard($this->card, $this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'by_reference'  => false,
                'required'      => false
            ))
            ->add('submitSave', 'submit', array('label' => $this->instance->translation . '.api.update.form.submit.save'));
    }

    public function getName()
    {
        return 'FhmUpdate';
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