<?php
namespace Fhm\GalleryBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\CreateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('testimony');
        parent::buildForm($builder, $options);
    }
}