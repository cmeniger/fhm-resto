<?php
namespace Fhm\MapPickerBundle\Form\Type\Admin;

use Fhm\FhmBundle\Form\Type\Admin\CreateType as FhmType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CreateType
 * @package Fhm\MapPickerBundle\Form\Type\Admin
 */
class CreateType extends FhmType
{
    private $maps = array();

    public function __construct($instance, $document, $maps)
    {
        parent::__construct($instance, $document);

        $this->maps['nomap'] = $options['translation_route'].'.nomap.choice';
        foreach ($maps as $map) {
            $this->maps[$map] = $options['translation_route'].'.'.$map.'.choice';
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setTranslation('mappicker');
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'order',
                NumberType::class,
                array('label' => $options['translation_route'].'.admin.create.form.order', 'required' => false)
            )
            ->add(
                'map',
                ChoiceType::class,
                array('choices' => $this->maps, 'label' => $options['translation_route'].'.admin.create.form.map')
            )
            ->remove('global');
    }
}