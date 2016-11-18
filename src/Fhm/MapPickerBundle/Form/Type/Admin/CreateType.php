<?php
namespace Fhm\MapPickerBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    private $maps = array();

    public function __construct($instance, $document, $maps)
    {
        parent::__construct($instance, $document);

        $this->maps['nomap'] = $this->instance->translation . '.nomap.choice';
        foreach($maps as $map)
        {
            $this->maps[$map] = $this->instance->translation . '.' . $map . '.choice';
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('order', 'number', array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('map', 'choice', array('choices' => $this->maps, 'label' => $this->instance->translation . '.admin.create.form.map'))
            ->remove('global');
    }
}