<?php
namespace Fhm\PartnerBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Fhm\PartnerBundle\Form\Type\Admin\Group\AddType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('content', TextareaType::class, array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor')))
            ->add('link', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.link', 'required' => false))
            ->add('order', IntegerType::class, array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->add('image', MediaType::class, array(
                    'label'  => $this->instance->translation . '.admin.create.form.image',
                    'filter' => 'image/*'
                )
            )
            ->add('partnergroups', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.partnergroups',
                'class'         => 'FhmPartnerBundle:PartnerGroup',
                'choice_label'      => 'name',
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