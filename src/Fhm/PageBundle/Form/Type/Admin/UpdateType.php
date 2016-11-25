<?php
namespace Fhm\PageBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('page');
        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\PageBundle\Document\Page',
                'translation_domain' => 'FhmPageBundle',
                'cascade_validation' => true,
            )
        );
    }
}