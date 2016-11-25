<?php
namespace Fhm\ArticleBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Doctrine\Bundle\MongoDBBundle\Tests\Fixtures\Form\Document;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\FormBuilderInterface;
use Fhm\FhmBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 * @package Fhm\ArticleBundle\Form\Type\Admin
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('article');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'title',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.title'
                )
            )
            ->add(
                'title',
                TextType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.title'
                )
            )
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $this->translation.'.admin.update.form.subtitle', 'required' => false)
            )
            ->add(
                'resume',
                TextareaType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.resume',
                    'attr' => array('class' => 'editor'),
                )
            )
            ->add(
                'content',
                TextareaType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.content',
                    'attr' => array('class' => 'editor'),
                )
            )
            ->add(
                'image',
                MediaType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.image',
                    'filter' => 'image/*',
                    'required' => false,
                )
            )
            ->add(
                'gallery',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.gallery',
                    'class' => 'FhmGalleryBundle:Gallery',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                )
            )
            ->add(
                'author',
                AutocompleteType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.author',
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
                'data_class' => 'Fhm\ArticleBundle\Document\Article',
                'translation_domain' => 'FhmArticleBundle',
                'cascade_validation' => true,
            )
        );
    }
}