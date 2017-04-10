<?php
namespace Project\ProductBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.title',
            ))
            ->add(
                'media',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.gallery',
                    'class' => 'FhmMediaBundle:Media',
                    'query_builder' => function () use ($options) {
                        $dr = $options['object_manager']->getCurrentRepository('FhmMediaBundle:Media');
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add('price', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.price',
            ))
            ->add('ingredient', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.update.form.ingredient',
                'mapped' => false,
                'required' => false
            ))
            ->add('parent',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.parent',
                    'class'         => 'FhmProductBundle:ProductIngredient',
                    'query_builder' => function () use ($options) {
                        $dr = $options['object_manager']->getCurrentRepository('FhmProductBundle:ProductIngredient');
                        return $dr->getFormEnable();
                    },
                    'choice_label'      => 'route',
                    'mapped' => false,
                    'required' => false
                ))
            ->add('ingredients',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.ingredients',
                    'class'         => 'FhmProductBundle:ProductIngredient',
                    'query_builder' => function () use ($options) {
                        $dr = $options['object_manager']->getCurrentRepository('FhmProductBundle:ProductIngredient');
                        return $dr->getFormEnable();
                    },
                    'choice_label'      => 'route',
                    'multiple'      => true,
                    'required'      => false,
                    'by_reference'  => false
                ))
            ->add('categories',
                TypeManager::getType($options['object_manager']->getDBDriver()),
                array(
                    'label' => $options['translation_route'] . '.admin.update.form.categories',
                    'class'         => 'FhmCategoryBundle:Category',
                    'query_builder' => function () use ($options) {
                        $dr = $options['object_manager']->getCurrentRepository('FhmCategoryBundle:Category');
                        return $dr->setParent(false)->getFormEnable();
                    },
                    'choice_label'      => 'route',
                    'required'      => false,
                    'multiple'      => true,
                    'by_reference'  => false
                ))
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
                'translation_route' => 'product',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
}