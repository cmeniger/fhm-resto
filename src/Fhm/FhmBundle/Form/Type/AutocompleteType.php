<?php
namespace Fhm\FhmBundle\Form\Type;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Class AutocompleteType
 * @package Fhm\FhmBundle\Form\Type
 */
class AutocompleteType extends AbstractType
{
    protected $router;

    /**
     * AutocompleteType constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'FhmFhmBundle',
                'choice_label' => 'name',
                'cascade_validation' => true,
                'url' => '',
                'class'=>'',
                'label'=>'',
                'required'=>''
            )
        );
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('url', $options)) {
            $route = is_array($options['url']) ? $options['url'][0] : $options['url'];
            $parameters = is_array($options['url']) ? $options['url'][1] : array();
            $view->vars['url'] = $this->router->generate($route, $parameters);
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
       DocumentType::class;
    }

}