<?php

namespace Fhm\FhmBundle\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\FhmBundle\Form\Type\Front
 */
class UpdateType extends AbstractType
{
    protected $translation;

    /**
     * @param $domaine
     */
    public function setTranslation($domaine = 'fhm')
    {
        $options['translation_route'] = $domaine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => $options['translation_route'].'.front.update.form.name'))
            ->add(
                'description',
                TextareaType::class,
                array('label' => $options['translation_route'].'.front.update.form.description', 'required' => false)
            )
            ->add(
                'submitSave',
                SubmitType::class,
                array('label' => $options['translation_route'].'.front.update.form.submit.save')
            )
            ->add(
                'submitNew',
                SubmitType::class,
                array('label' => $options['translation_route'].'.front.update.form.submit.new')
            )
            ->add(
                'submitDuplicate',
                SubmitType::class,
                array('label' => $options['translation_route'].'.front.update.form.submit.duplicate')
            )
            ->add(
                'submitQuit',
                SubmitType::class,
                array('label' => $options['translation_route'].'.front.update.form.submit.quit')
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'FhmUpdate';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\FhmBundle\Document\Fhm',
                'translation_domain' => 'FhmFhmBundle',
                'cascade_validation' => true,
                'translation_route'=>'',
                'filter'=>'',
                'lang_visible'=>'',
                'lang_available'=>'',
                'grouping_visible'=>'',
                'grouping_available'=>'',
                'user_admin'=>''
            )
        );
    }
}