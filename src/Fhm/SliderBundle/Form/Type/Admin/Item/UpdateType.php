<?php
namespace Fhm\SliderBundle\Form\Type\Admin\Item;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Fhm\MediaBundle\Form\Type\MediaType;
use Fhm\SliderBundle\Repository\SliderRepository;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            'title',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.title')
        )->add(
            'subtitle',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.subtitle', 'required' => false)
        )->add(
            'content',
            TextareaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.content',
                'attr' => array('class' => 'editor'),
                'required' => false,
            )
        )->add(
            'link',
            TextType::class,
            array('label' => $options['translation_route'].'.admin.update.form.link', 'required' => false)
        )->add(
            'order',
            NumberType::class,
            array('label' => $options['translation_route'].'.admin.update.form.order', 'required' => false)
        )->add(
            'image',
            MediaType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.image',
                'filter' => 'image/*',
            )
        )->add(
            'sliders',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.update.form.sliders',
                'class' => 'FhmSliderBundle:Slider',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmSliderBundle:Slider');
                    return $dr->getFormEnable($options['filter']);
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->remove('name')->remove('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\SliderBundle\Document\SliderItem',
                'translation_domain' => 'FhmSliderBundle',
                'cascade_validation' => true,
                'translation_route' => 'slider.item',
                'filter' => '',
                'user_admin' => '',
                'map' => '',
            )
        );
    }

}