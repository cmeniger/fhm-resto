<?php
namespace Fhm\PartnerBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor')))
            ->add('link', 'text', array('label' => $this->instance->translation . '.admin.update.form.link', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.update.form.order', 'required' => false))
            ->add('image', 'media', array(
                    'label'  => $this->instance->translation . '.admin.update.form.image',
                    'filter' => 'image/*'
                )
            )
            ->add('partnergroups', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.partnergroups',
                'class'         => 'FhmPartnerBundle:PartnerGroup',
                'property'      => 'name',
                'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerGroupRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->remove('description');
    }
}