<?php
namespace Fhm\PartnerBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\PartnerBundle\Form\Type\Admin
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
                'content',
                TextareaType::class,
                array('label' => $this->translation.'.admin.update.form.content', 'attr' => array('class' => 'editor'))
            )
            ->add(
                'link',
                TextType::class,
                array('label' => $this->translation.'.admin.update.form.link', 'required' => false)
            )
            ->add(
                'order',
                IntegerType::class,
                array('label' => $this->translation.'.admin.update.form.order', 'required' => false)
            )
            ->add(
                'image',
                MediaType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.image',
                    'filter' => 'image/*',
                )
            )
            ->add(
                'partnergroups',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.partnergroups',
                    'class' => 'FhmPartnerBundle:PartnerGroup',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\PartnerBundle\Repository\PartnerGroupRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                )
            )
            ->remove('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\PartnerBundle\Document\Partner',
                'translation_domain' => 'FhmPartnerBundle',
                'cascade_validation' => true,
            )
        );
    }
}