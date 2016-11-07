<?php
/**
 * Created by PhpStorm.
 * User: rcisse
 * Date: 26/10/16
 * Time: 11:47
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CkeditorType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'ckeditor') // On ajoute la classe
        ));
    }

    public function getParent() // On utilise l'héritage de formulaire
    {
        return 'textarea';
    }

    public function getBlockPrefix()
    {
        return 'ckeditor';
    }
}