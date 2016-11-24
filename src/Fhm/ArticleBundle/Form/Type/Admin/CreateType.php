<?php
namespace Fhm\ArticleBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\FhmBundle\Form\Type\AutocompleteType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder

            ->add(
                'title',
                TextType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.title'
                )
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $this->instance->translation.'.admin.create.form.subtitle', 'required' => false)
            )
            ->add(
                'resume',
                TextareaType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.resume',
                    'attr' => array('class' => 'editor'),
                )
            )
            ->add(
                'content',
                TextareaType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.content',
                    'attr' => array('class' => 'editor'),
                )
            )
            ->add(
                'image',
                MediaType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.image',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'gallery',
                DocumentType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.gallery',
                    'class' => 'FhmGalleryBundle:Gallery',
                    'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr) {
                        return $dr->getFormEnable($this->instance->grouping->filtered);
                    },
                    'required' => false,
                )
            )
            ->add(
                'author',
                AutocompleteType::class,
                array(
                    'label' => $this->instance->translation.'.admin.create.form.author',
                    'class' => 'FhmUserBundle:User',
                    'url' => 'fhm_api_user_autocomplete',
                    'required' => false,
                )
            )
            ->remove('global')
            ->remove('name')
            ->remove('description');
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => null,
                'translation_domain' => 'FhmArticleBundle',
                'cascade_validation' => true,
            )
        );
    }
}