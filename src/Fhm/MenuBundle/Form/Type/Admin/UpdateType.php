<?php
namespace Fhm\MenuBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MenuBundle\Form\Type\Extension\LinkType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('menu');
        parent::buildForm($builder, $options);
        $builder
            ->add('icon', TextType::class, array('label' => $this->instance->translation . '.admin.update.form.icon', 'required' => false))
            ->add('route', new LinkType(), array('label' => $this->instance->translation . '.admin.update.form.route', 'required' => false))
            ->remove('share')
            ->remove('global');
    }
}