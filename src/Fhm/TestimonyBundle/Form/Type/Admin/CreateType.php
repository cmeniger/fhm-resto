<?php
namespace Fhm\TestimonyBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('testimony');
        parent::buildForm($builder, $options);
        $builder
            ->add('image', MediaType::class, array(
                'label'    => $this->translation . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ));
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