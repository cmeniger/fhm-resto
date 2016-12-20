<?php
namespace Fhm\EventBundle\Form\Type\Admin\Group;

use Fhm\EventBundle\Repository\EventRepository;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\EventBundle\Form\Type\Admin\Group
 */
class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'add_global',
            'checkbox',
            array('label' => $options['translation_route'].'.admin.create.form.add_global', 'required' => false)
        )->add(
            'events',
            'document',
            array(
                'label' => $options['translation_route'].'.admin.create.form.events',
                'class' => 'FhmEventBundle:Event',
                'choice_label' => 'name',
                'query_builder' => function (EventRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->remove('global');
    }

}