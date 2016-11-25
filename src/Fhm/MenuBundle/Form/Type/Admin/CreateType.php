<?php
namespace Fhm\MenuBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MenuBundle\Form\Type\Extension\LinkType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('menu');
        parent::buildForm($builder, $options);
        $builder
            ->add('icon', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.icon', 'required' => false))
            ->add('route', new LinkType(), array('label' => $this->instance->translation . '.admin.create.form.route', 'required' => false))
            ->add('id', HiddenType::class, array('mapped' => false, 'attr' => array('value' => $this->document->getId())))
            ->remove('share')
            ->remove('global');
    }
}