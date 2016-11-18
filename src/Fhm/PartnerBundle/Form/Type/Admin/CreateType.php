<?php
namespace Fhm\PartnerBundle\Form\Type\Admin;

use Fhm\PartnerBundle\Form\Type\Admin\Group\AddType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor')))
            ->add('link', 'text', array('label' => $this->instance->translation . '.admin.create.form.link', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('image', 'media', array(
                    'label'  => $this->instance->translation . '.admin.create.form.image',
                    'filter' => 'image/*'
                )
            )
            ->add('partnergroups', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.partnergroups',
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