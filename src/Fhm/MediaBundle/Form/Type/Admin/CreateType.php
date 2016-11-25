<?php
namespace Fhm\MediaBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('media');
        parent::buildForm($builder, $options);
        $builder
            ->add('name', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.name', 'required' => false))
            ->add('file', FileType::class, array('label' => $this->instance->translation . '.admin.create.form.file'))
            ->add('tag', TextType::class, array('label' => $this->instance->translation . '.admin.create.form.tag', 'mapped' => false, 'required' => false))
            ->add('private', CheckboxType::class, array('label' => $this->instance->translation . '.admin.create.form.private', 'required' => false))
            ->add('parent', DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.parent',
                'class'         => 'FhmMediaBundle:MediaTag',
                'choice_label'      => 'route',
                'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->getFormFiltered($this->instance->grouping->filtered);
                },
                'mapped'        => false,
                'required'      => false
            ))
            ->add('tags',DocumentType::class, array(
                'label'         => $this->instance->translation . '.admin.create.form.tags',
                'class'         => 'FhmMediaBundle:MediaTag',
                'choice_label'      => 'route',
                'query_builder' => function (\Fhm\MediaBundle\Repository\MediaTagRepository $dr)
                {
                    return $dr->getFormFiltered($this->instance->grouping->filtered);
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->remove('seo_title')
            ->remove('seo_description')
            ->remove('seo_keywords')
            ->remove('languages')
            ->remove('grouping')
            ->remove('share')
            ->remove('global')
            ->remove('submitNew')
            ->remove('submitDuplicate')
            ->remove('submitQuit')
            ->remove('submitConfig');
    }
}