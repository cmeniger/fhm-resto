<?php
namespace Fhm\NewsBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\UpdateType as FhmType;
use Fhm\FhmBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('news');
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->translation . '.admin.update.form.title'))
            ->add('subtitle', TextType::class, array('label' => $this->translation . '.admin.update.form.subtitle', 'required' => false))
            ->add('resume', TextareaType::class, array('label' => $this->translation . '.admin.update.form.resume', 'attr' => array('class' => 'editor')))
            ->add('content', TextareaType::class, array('label' => $this->translation . '.admin.update.form.content', 'attr' => array('class' => 'editor')))
            ->add('date_start', DateTimeType::class, array('label' => $this->translation . '.admin.update.form.start', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker'), 'required' => false))
            ->add('date_end', DateTimeType::class, array('label' => $this->translation . '.admin.update.form.end', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker'), 'required' => false))
            ->add('image', 'media', array(
                    'label'    => $this->translation . '.admin.update.form.image',
                    'filter'   => 'image/*',
                    'required' => false
                )
            )
            ->add('gallery', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.gallery',
                'class'         => 'FhmGalleryBundle:Gallery',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\GalleryBundle\Repository\GalleryRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'required'      => false
            ))
            ->add('newsgroups', DocumentType::class, array(
                'label'         => $this->translation . '.admin.update.form.newsgroups',
                'class'         => 'FhmNewsBundle:NewsGroup',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\NewsBundle\Repository\NewsGroupRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'multiple'      => true,
                'required'      => false,
                'by_reference'  => false
            ))
            ->add('author', AutocompleteType::class, array(
                'label'    => $this->translation . '.admin.update.form.author',
                'class'    => 'FhmUserBundle:User',
                'url'      => 'fhm_api_user_autocomplete',
                'required' => false
            ))
            ->remove('name')
            ->remove('description');
    }
}