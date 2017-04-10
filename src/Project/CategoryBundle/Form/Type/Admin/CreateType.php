<?php
namespace Project\CategoryBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'summernote'), 'required' => false))
            ->add('parent', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.parent',
                'class'         => 'FhmCategoryBundle:Category',
                'property'      => 'route',
                'query_builder' => function (\Fhm\CategoryBundle\Repository\CategoryRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false
            ))
            ->add('activeimage', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.activeimage', 'required' => false))
            ->add('activemenumode', 'checkbox', array('label' => $this->instance->translation . '.admin.create.form.activemenumode', 'required' => false))
            ->add('price', 'text', array('label' => $this->instance->translation . '.admin.create.form.price', 'required' => false))
            ->add('order', 'integer', array('label' => $this->instance->translation . '.admin.create.form.order', 'required' => false))
            ->remove('share')
            ->remove('global');
    }
}