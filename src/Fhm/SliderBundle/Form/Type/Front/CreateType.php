<?php
namespace Fhm\SliderBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('slider');
        parent::buildForm($builder, $options);
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\SliderBundle\Document\Slider',
                'translation_domain' => 'FhmSliderBundle',
                'cascade_validation' => true,
            )
        );
    }

}