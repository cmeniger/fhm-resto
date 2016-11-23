<?php
namespace Fhm\ArticleBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Doctrine\Bundle\MongoDBBundle\Tests\Fixtures\Form\Document;
use Symfony\Component\Form\FormBuilderInterface;
use Fhm\FhmBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', 'text', array('label' => $this->instance->translation . '.admin.update.form.title'))
            ->add('title', TextType::class, array('label' => $this->instance->translation . '.admin.update.form.title'))
            ->add('subtitle', TextType::class, array('label' => $this->instance->translation . '.admin.update.form.subtitle', 'required' => false))
            ->add('resume', TextareaType::class, array('label' => $this->instance->translation . '.admin.update.form.resume', 'attr' => array('class' => 'editor')))
            ->add('content',TextareaType::class, array('label' => $this->instance->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor')))
            ->add('image', 'media', array(
                    'label'    => $this->instance->translation . '.admin.update.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                )
            )
            ->add('gallery', Document::class, array(
                'label'         => $this->instance->translation . '.admin.update.form.gallery',
                'class'         => 'FhmGalleryBundle:Gallery',
                'property'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                {
                    return $dr->getFormEnable($this->instance->grouping->filtered);
                },
                'required'      => false
            ))
            ->add('author', AutocompleteType::class, array(
                'label'    => $this->instance->translation . '.admin.update.form.author',
                'class'    => 'FhmUserBundle:User',
                'url'      => 'fhm_api_user_autocomplete',
                'required' => false
            ))
            ->remove('global')
            ->remove('name')
            ->remove('description');
    }
}