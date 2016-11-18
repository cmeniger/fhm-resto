<?php
namespace Fhm\EventBundle\Form\Type\Admin\Group;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('add_global', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.add_global', 'required' => false))
            ->add('events', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.events',
                'class'         => 'FhmEventBundle:Event',
                'property'      => 'name',
                'query_builder' => function (\Fhm\EventBundle\Repository\EventRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->remove('global');
    }
}