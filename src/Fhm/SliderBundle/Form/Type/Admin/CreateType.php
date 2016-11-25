<?php
namespace Fhm\SliderBundle\Form\Type\Admin;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Fhm\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends FhmType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('slider');
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, array('label' => $this->translation . '.admin.create.form.title'))
            ->add('subtitle',TextType::class, array('label' => $this->translation . '.admin.create.form.subtitle', 'required' => false))
            ->add('resume', TextareaType::class, array('label' => $this->translation . '.admin.create.form.resume', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('content', TextareaType::class, array('label' => $this->translation . '.admin.create.form.content', 'attr' => array('class' => 'editor'), 'required' => false))
            ->add('add_global', CheckboxType::class, array('label' => $this->translation . '.admin.create.form.add_global', 'required' => false))
            ->add('sort', ChoiceType::class, array('label' => $this->translation . '.admin.create.form.sort', 'choices' => $this->_sortChoices()))
            ->add('image', MediaType::class, array(
                'label'    => $this->translation . '.admin.create.form.image',
                'filter'   => 'image/*',
                'required' => false
            ))
            ->add('items', DocumentType::class, array(
                'label'         => $this->translation . '.admin.create.form.items',
                'class'         => 'FhmSliderBundle:SliderItem',
                'choice_label'      => 'name',
                'query_builder' => function (\Fhm\SliderBundle\Repository\SliderItemRepository $dr)
                {
                    return $dr->getFormEnable();
                },
                'required'      => false,
                'multiple'      => true,
                'by_reference'  => false
            ))
            ->remove('name')
            ->remove('description');
    }

    private function _sortChoices()
    {
        return array
        (
            "title"            => $this->translation . '.admin.sort.title.asc',
            "title desc"       => $this->translation . '.admin.sort.title.desc',
            "order"            => $this->translation . '.admin.sort.order.asc',
            "order desc"       => $this->translation . '.admin.sort.order.desc',
            "date_create"      => $this->translation . '.admin.sort.create.asc',
            "date_create desc" => $this->translation . '.admin.sort.create.desc',
            "date_update"      => $this->translation . '.admin.sort.update.asc',
            "date_update desc" => $this->translation . '.admin.sort.update.desc'
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Fhm\SliderBundle\Document\Slider',
                'translation_domain' => 'FhmSliderBundle',
                'cascade_validation' => true,
            )
        );
    }
}