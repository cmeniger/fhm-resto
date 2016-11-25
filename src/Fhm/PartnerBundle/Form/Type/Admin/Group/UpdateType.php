<?php
namespace Fhm\PartnerBundle\Form\Type\Admin\Group;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('partner');
        parent::buildForm($builder, $options);
        $builder
            ->add('add_global', CheckboxType::class, array('label' => $this->instance->translation . '.admin.update.form.add_global', 'required' => false))
            ->add('partners', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.partners',
                'class'         => 'FhmPartnerBundle:Partner',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerRepository $dr)
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