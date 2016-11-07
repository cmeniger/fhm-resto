<?php
namespace Fhm\FhmBundle\Form\Type;

use Fhm\FhmBundle\Form\DataTransformer\SchedulesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SchedulesType extends AbstractType
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'FhmFhmBundle',
            'cascade_validation' => true,
            'by_reference'       => false,
            'label'              => 'fhm.schedules.title',
            'step'               => 15,
            'editor'             => false,
            'subscriber'         => null
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['subscriber']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $accessor   = PropertyAccess::createPropertyAccessor();
        $subscriber = $options['subscriber'];
        if($accessor->isReadable($subscriber['data'], $builder->getName()))
        {
            $options['data']       = $accessor->getValue($subscriber['data'], $builder->getName());
            $options['data_class'] = $subscriber['data_class'];
        }
        for($i = 1; $i < 8; $i++)
        {
            $builder
                ->add('days_' . $i . '_0', 'choice', array('label' => 'fhm.schedules.day.start', 'choices' => $this->_listTime($options['step']), 'attr' => array('class' => 'am'), 'required' => false))
                ->add('days_' . $i . '_1', 'choice', array('label' => 'fhm.schedules.day.end', 'choices' => $this->_listTime($options['step']), 'attr' => array('class' => 'am'), 'required' => false))
                ->add('days_' . $i . '_2', 'choice', array('label' => 'fhm.schedules.day.start', 'choices' => $this->_listTime($options['step']), 'attr' => array('class' => 'pm'), 'required' => false))
                ->add('days_' . $i . '_3', 'choice', array('label' => 'fhm.schedules.day.end', 'choices' => $this->_listTime($options['step']), 'attr' => array('class' => 'pm'), 'required' => false));
        }
        $builder
            ->add('close_enable', 'checkbox', array('label' => 'fhm.schedules.close.check', 'required' => false))
            ->add('close_reason', 'textarea', array('label' => 'fhm.schedules.close.reason', 'required' => false, 'attr' => array('class' => $options['editor'] ? 'editor' : '')))
            ->add('close_date', 'datetime', array('label' => 'fhm.schedules.close.date', 'widget' => 'single_text', 'input' => 'datetime', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datetimepicker', 'step' => $options['step']), 'required' => false))
            ->addModelTransformer(new SchedulesTransformer($this->container));
    }

    /**
     * @param \Symfony\Component\Form\FormView      $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array                                 $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'schedules';
    }

    /**
     * @param int    $step
     * @param string $type
     *
     * @return array
     */
    private function _listTime($step = 1, $type = '')
    {
        $list  = array(
            'close' => 'fhm.schedules.day.close'
        );
        $start = $type == 'pm' ? 13 : 0;
        $end   = $type == 'am' ? 13 : 24;
        for($i = $start; $i <= $end; $i++)
        {
            for($j = 0; $j < 60; $j = $j + $step)
            {
                $key        = str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($j, 2, '0', STR_PAD_LEFT);
                $list[$key] = $key;
                if($i == $end)
                {
                    break;
                }
            }
        }

        return $list;
    }
}