<?php
namespace Project\CategoryBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends FhmType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('content', TextareaType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.content',
                'attr' => array('class' => 'summernote'),
                'required' => false
                )
            )
            ->add('parent', TypeManager::getType($options['object_manager']->getDBDriver()), array(
                'label'         => $options['translation_route'] . '.admin.create.form.parent',
                'class'         => 'FhmCategoryBundle:Category',
                'choice_label'  => 'route',
                'query_builder' => function (\Project\CategoryBundle\Document\Repository\CategoryRepository $dr)
                    {
                        return $dr->getFormEnable();
                    },
                'required'      => false
            ))
            ->add('activeimage', CheckboxType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.activeimage',
                'required' => false
            ))
            ->add('activemenumode', CheckboxType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.activemenumode',
                'required' => false
            ))
            ->add('price', TextType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.price',
                'required' => false
            ))
            ->add('order', IntegerType::class, array(
                'label' => $options['translation_route'] . '.admin.create.form.order',
                'required' => false
            ))
            ->remove('share')
            ->remove('global');
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '',
                'translation_domain' => 'FhmCategoryBundle',
                'cascade_validation' => true,
                'translation_route' => 'category',
                'user_admin' => '',
                'object_manager' => ''
            )
        );
    }
}