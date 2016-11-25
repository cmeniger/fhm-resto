<?php
namespace Fhm\TestimonyBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('testimony');
        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\TestimonyBundle\Document\Testimony',
                'translation_domain' => 'FhmTestimonyBundle',
                'cascade_validation' => true,
            )
        );
    }
}