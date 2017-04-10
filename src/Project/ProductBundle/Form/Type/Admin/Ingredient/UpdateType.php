<?php
namespace Project\ProductBundle\Form\Type\Admin\Ingredient;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'parent',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.parent',
                    'class' => 'FhmProductBundle:ProductIngredient',
                    'query_builder' => function () use ($options) {
                        $dr = $options['object_manager']->getCurrentRepository('FhmProductBundle:ProductIngredient');
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
        ;
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmProductBundle',
                'cascade_validation' => true,
                'translation_route' => 'product.ingredient',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
}