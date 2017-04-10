<?php
namespace Project\ProductBundle\Form\Type\Admin\Ingredient;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'parent',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'] . '.admin.create.form.parent',
                    'class' => 'ProjectProductBundle:ProductIngredient',
                    'query_builder' => function () use ($options) {
                        $dr = $options['object_manager']->getCurrentRepository('ProjectProductBundle:ProductIngredient');
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
        ;
    }
}