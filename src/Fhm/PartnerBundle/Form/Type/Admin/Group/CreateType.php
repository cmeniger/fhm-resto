<?php
namespace Fhm\PartnerBundle\Form\Type\Admin\Group;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\PartnerBundle\Repository\PartnerRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\PartnerBundle\Form\Type\Admin\Group
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
            CheckboxType::class,
            array('label' => $options['translation_route'].'.admin.create.form.add_global', 'required' => false)
        )->add(
            'partners',
            DocumentType::class,
            array(
                'label' => $options['translation_route'].'.admin.create.form.partners',
                'class' => 'FhmPartnerBundle:Partner',
                'choice_label' => 'name',
                'query_builder' => function (PartnerRepository $dr) use ($options) {
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->remove('global');
    }
}