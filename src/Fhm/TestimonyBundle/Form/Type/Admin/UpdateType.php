<?php
namespace Fhm\TestimonyBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function __construct($instance, $document)
    {
        parent::__construct($instance, $document);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('image', 'media', array(
                'label'    => $this->instance->translation . '.admin.update.form.image',
                'filter'   => 'image/*',
                'required' => false
            ));
    }
}