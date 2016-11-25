<?php
namespace Fhm\TestimonyBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function __construct($instance, $document)
    {
        parent::__construct($instance, $document);
    }

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
}