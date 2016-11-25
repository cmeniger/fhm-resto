<?php
namespace Fhm\EventBundle\Form\Type\Admin\Group;

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
        $this->setTranslation('group');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'add_global',
                'checkbox',
                array('label' => $this->translation.'.admin.update.form.add_global', 'required' => false)
            )
            ->add(
                'events',
                'document',
                array(
                    'label' => $this->translation.'.admin.update.form.events',
                    'class' => 'FhmEventBundle:Event',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\EventBundle\Repository\EventRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                    'multiple' => true,
                    'by_reference' => false,
                )
            )
            ->remove('global');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\EventBundle\Document\Group',
                'translation_domain' => 'FhmEventBundle',
                'cascade_validation' => true,
            )
        );
    }
}