<?php
namespace Fhm\NewsBundle\Form\Type\Admin\Group;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Manager\TypeManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\NewsBundle\Form\Type\Admin\Group
 */
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
            'add_global',
            CheckboxType::class,
            array('label' => $options['translation_route'].'.admin.update.form.add_global', 'required' => false)
        )->add(
            'sort',
            ChoiceType::class,
            array(
                'label' => $options['translation_route'].'.admin.update.form.sort',
                'choices' => $this->sortChoices($options),
            )
        )->add(
            'news',
            TypeManager::getType($options['object_manager']->getDBDriver()),
            array(
                'label' => $options['translation_route'].'.admin.update.form.news',
                'class' => 'FhmNewsBundle:News',
                'choice_label' => 'name',
                'query_builder' => function () use ($options) {
                    $dr = $options['object_manager']->getCurrentRepository('FhmNewsBundle:News');
                    return $dr->getFormEnable();
                },
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            )
        )->remove('global');
    }

    /**
     * @return array
     */
    private function sortChoices($options)
    {
        return array(
            "title" => $options['translation_route'].'.admin.sort.title.asc',
            "title desc" => $options['translation_route'].'.admin.sort.title.desc',
            "date_start" => $options['translation_route'].'.admin.sort.start.asc',
            "date_start desc" => $options['translation_route'].'.admin.sort.start.desc',
            "date_create" => $options['translation_route'].'.admin.sort.create.asc',
            "date_create desc" => $options['translation_route'].'.admin.sort.create.desc',
            "date_update" => $options['translation_route'].'.admin.sort.update.asc',
            "date_update desc" => $options['translation_route'].'.admin.sort.update.desc',
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\NewsBundle\Document\NewsGroup',
                'translation_domain' => 'FhmNewsBundle',
                'cascade_validation' => true,
            )
        );
    }
}