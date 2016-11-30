<?php
namespace Fhm\PartnerBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Fhm\PartnerBundle\Form\Type\Admin\Group\AddType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\PartnerBundle\Repository\PartnerGroupRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 * @package Fhm\PartnerBundle\Form\Type\Admin
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
        $builder
            ->add(
                'content',
                TextareaType::class,
                array('label' => $options['translation_route'].'.admin.create.form.content',
                      'attr' => array('class' => 'editor'))
            )
            ->add(
                'link',
                TextType::class,
                array('label' => $options['translation_route'].'.admin.create.form.link', 'required' => false)
            )
            ->add(
                'order',
                IntegerType::class,
                array('label' => $options['translation_route'].'.admin.create.form.order', 'required' => false)
            )
            ->add(
                'image',
                MediaType::class,
                array(
                    'label' => $options['translation_route'].'.admin.create.form.image',
                    'filter' => 'image/*',
                )
            )
            ->add(
                'partnergroups',
                DocumentType::class,
                array(
                    'label' => $options['translation_route'].'.admin.create.form.partnergroups',
                    'class' => 'FhmPartnerBundle:PartnerGroup',
                    'choice_label' => 'name',
                    'query_builder' => function (PartnerGroupRepository $dr) use ($options) {
                        return $dr->getFormEnable($options['filter']);
                    },
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                )
            )
            ->remove('description');
    }

}