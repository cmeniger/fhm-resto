<?php
namespace Fhm\EventBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', 'text', array('label' => $this->instance->translation . '.admin.update.form.title'))
            ->add('subtitle', 'text', array('label' => $this->instance->translation . '.admin.update.form.subtitle', 'required' => false))
            ->add('resume', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.resume', 'attr' => array('class' => 'editor')))
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor')))
            ->add('date_start', 'datetime', array('label' => $this->instance->translation . '.admin.create.form.start', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker')))
            ->add('date_end', 'datetime', array('label' => $this->instance->translation . '.admin.create.form.end', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker')))
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('eventgroups', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.eventgroups',
                'class'         => 'FhmEventBundle:EventGroup',
                'property'      => 'name',
                'query_builder' => function (\Fhm\EventBundle\Repository\EventGroupRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->remove('name')
            ->remove('description');
    }
}