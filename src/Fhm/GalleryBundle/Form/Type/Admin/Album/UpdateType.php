<?php
namespace Fhm\GalleryBundle\Form\Type\Admin\Album;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 *
 * @package Fhm\GalleryBundle\Form\Type\Admin\Album
 */
class UpdateType extends FhmType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('gallery.album');
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->translation.'.admin.update.form.title'))
            ->add(
                'subtitle',
                TextType::class,
                array('label' => $this->translation.'.admin.update.form.subtitle', 'required' => false)
            )
            ->add(
                'content',
                TextareaType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.content',
                    'attr' => array('class' => 'editor'),
                    'required' => false,
                )
            )
            ->add(
                'add_global',
                CheckboxType::class,
                array('label' => $this->translation.'.admin.update.form.add_global', 'required' => false)
            )
            ->add(
                'sort',
                ChoiceType::class,
                array('label' => $this->translation.'.admin.update.form.sort', 'choices' => $this->_sortChoices())
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
                'galleries',
                DocumentType::class,
                array(
                    'label' => $this->translation.'.admin.update.form.galleries',
                    'class' => 'FhmGalleryBundle:Gallery',
                    'choice_label' => 'name',
                    'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr) {
                        return $dr->getFormEnable();
                    },
                    'required' => false,
                    'multiple' => true,
                    'by_reference' => false,
                )
            )
            ->remove('name')
            ->remove('description');
    }

    /**
     * @return array
     */
    private function _sortChoices()
    {
        return array
        (
            "title" => $this->translation.'.admin.sort.title.asc',
            "title desc" => $this->translation.'.admin.sort.title.desc',
            "order" => $this->translation.'.admin.sort.order.asc',
            "order desc" => $this->translation.'.admin.sort.order.desc',
            "date_create" => $this->translation.'.admin.sort.create.asc',
            "date_create desc" => $this->translation.'.admin.sort.create.desc',
            "date_update" => $this->translation.'.admin.sort.update.asc',
            "date_update desc" => $this->translation.'.admin.sort.update.desc',
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\GalleryBundle\Document\GalleryAlbum',
                'translation_domain' => 'FhmGalleryBundle',
                'cascade_validation' => true,
            )
        );
    }
}