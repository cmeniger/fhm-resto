<?php
namespace Fhm\MapPickerBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
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
        $this->setTranslation('mappicker');
        parent::buildForm($builder, $options);
        $builder
            ->add('order', NumberType::class, array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('map', ChoiceType::class, array('choices' => $this->maps, 'label' => $this->instance->translation . '.admin.create.form.map'))
            ->remove('global');
    }
}