<?php
namespace Fhm\NewsBundle\Form\Type\Admin\Tag;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**.
 * Class UpdateType
 * @package Fhm\NewsBundle\Form\Type\Admin\Tag
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options .
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\NewsBundle\Document\NewsTag',
                'translation_domain' => 'FhmNewsBundle',
                'cascade_validation' => true,
            )
        );
    }
}