<?php
namespace Fhm\FhmBundle\Form\Type;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutocompleteType extends AbstractType
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'property'           => 'name',
            'cascade_validation' => true,
            'url'                => '',
            'attr'               => array(
                'placeholder' => $this->container->get('translator')->trans(
                    'fhm.autocomplete.placeholder',
                    array(),
                    'FhmFhmBundle'
                )
            )
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormView      $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array                                 $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('url', $options)) {
            $route             = is_array($options['url']) ? $options['url'][0] : $options['url'];
            $parameters        = is_array($options['url']) ? $options['url'][1] : array();
            $view->vars['url'] = $this->container->get('router')->generate($route, $parameters);
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'autocomplete';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return DocumentType::class;
    }

}