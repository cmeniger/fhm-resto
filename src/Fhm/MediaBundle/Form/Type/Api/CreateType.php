<?php
namespace Fhm\MediaBundle\Form\Type\Api;

use Fhm\MediaBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    protected $root;

    public function __construct($instance, $document, $root)
    {
        parent::__construct($instance, $document);
        $this->root = $root;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('parent', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.parent',
                'class'         => 'FhmMediaBundle:MediaTag',
                'property'      => 'route',
                'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->setRoot($this->root)->getFormFiltered($this->instance->grouping->filtered);
                },
                'mapped'        => false,
                'required'      => false
            ))
            ->add('tags', 'document', array(
                'label'         => $this->instance->translation . '.admin.create.form.tags',
                'class'         => 'FhmMediaBundle:MediaTag',
                'property'      => 'route',
                'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->setRoot($this->root)->getFormFiltered($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->remove('private')
            ->remove('active')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global');
    }
}