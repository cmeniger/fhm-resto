<?php
namespace Fhm\NotificationBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('content', 'textarea', array('label' => $this->instance->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor')))
            ->add('user', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.user',
                'class'         => 'FhmUserBundle:User',
                'property'      => 'name',
                'query_builder' => function (\Fhm\UserBundle\Repository\UserRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                }
            ))
            ->remove('name')
            ->remove('description')
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global')
            ->remove('active');
    }
}