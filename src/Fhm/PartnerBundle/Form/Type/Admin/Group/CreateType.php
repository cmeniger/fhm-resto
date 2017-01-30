<?php
namespace Fhm\PartnerBundle\Form\Type\Admin\Group;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
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
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.create.form.partners',
                'class' => 'FhmPartnerBundle:Partner',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmPartnerBundle:Partner');
                    return $dr->getFormEnable();
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
                'data_class' => 'Fhm\PartnerBundle\Document\PartnerGroup',
                'translation_domain' => 'FhmPartnerBundle',
                'cascade_validation' => true,
                'translation_route' => 'partner.group',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
}