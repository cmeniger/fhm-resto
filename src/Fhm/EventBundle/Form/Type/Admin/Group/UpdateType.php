<?php
namespace Fhm\EventBundle\Form\Type\Admin\Group;

use Fhm\EventBundle\Repository\EventRepository;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\EventBundle\Form\Type\Admin\Group
 */
class UpdateType extends FhmType
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
            array('label' => $options['translation_route'].'.admin.update.form.add_global', 'required' => false)
        )->add(
            'events',
            'document',
            array(
                'label' => $options['translation_route'].'.admin.update.form.events',
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
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\EventBundle\Document\EventGroup',
                'translation_domain' => 'FhmEventBundle',
                'cascade_validation' => true,
                'translation_route' => 'event.group',
                'filter' => '',
                'user_admin' => '',
            )
        );
    }
}