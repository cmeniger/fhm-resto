<?php
namespace Fhm\NewsBundle\Form\Type\Admin\Group;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('add_global', 'checkbox', array('label' => $this->instance->translation . '.admin.update.form.add_global', 'required' => false))
            ->add('sort', 'choice', array('label' => $this->instance->translation . '.admin.update.form.sort', 'choices' => $this->_sortChoices()))
            ->add('news', 'document', array(
                'label'         => $this->instance->translation . '.admin.update.form.news',
                'class'         => 'FhmNewsBundle:News',
                'property'      => 'name',
                'query_builder' => function (\Fhm\NewsBundle\Repository\NewsRepository $dr)
                    {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->remove('global');
    }

    private function _sortChoices()
    {
        return array
        (
            "title"            => $this->instance->translation . '.admin.sort.title.asc',
            "title desc"       => $this->instance->translation . '.admin.sort.title.desc',
            "date_start"       => $this->instance->translation . '.admin.sort.start.asc',
            "date_start desc"  => $this->instance->translation . '.admin.sort.start.desc',
            "date_create"      => $this->instance->translation . '.admin.sort.create.asc',
            "date_create desc" => $this->instance->translation . '.admin.sort.create.desc',
            "date_update"      => $this->instance->translation . '.admin.sort.update.asc',
            "date_update desc" => $this->instance->translation . '.admin.sort.update.desc'
        );
    }
}