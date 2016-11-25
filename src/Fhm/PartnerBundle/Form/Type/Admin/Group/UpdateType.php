<?php
namespace Fhm\PartnerBundle\Form\Type\Admin\Group;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\PartnerBundle\Form\Type\Admin\Group
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('partner');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'add_global',
                CheckboxType::class,
                array('label' => $this->translation.'.admin.update.form.add_global', 'required' => false)
            )
            ->add(
                'partners',
                'document',
                array(
                    'label' => $this->translation.'.admin.update.form.partners',
                    'class' => 'FhmPartnerBundle:Partner',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerRepository $dr) {
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
                'data_class' => 'Fhm\PartnerBundle\Document\PartnerGroup',
                'translation_domain' => 'FhmPartnerBundle',
                'cascade_validation' => true,
            )
        );
    }
}