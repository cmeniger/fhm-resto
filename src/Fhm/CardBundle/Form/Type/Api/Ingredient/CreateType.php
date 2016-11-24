<?php
namespace Fhm\CardBundle\Form\Type\Api\Ingredient;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('order', 'integer', array('label' => $this->instance->translation . '.api.create.form.order', 'required' => false))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.api.create.form.image',
                'filter'   => 'image/*',
                'required' => false
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => null,
                'translation_domain' => 'FhmCardBundle',
                'cascade_validation' => true,
            )
        );
    }
}