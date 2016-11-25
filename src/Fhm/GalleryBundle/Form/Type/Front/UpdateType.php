<?php
namespace Fhm\GalleryBundle\Form\Type\Front;

use Fhm\FhmBundle\Form\Type\Front\UpdateType as FhmType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('gallery');
        parent::buildForm($builder, $options);
    }
}