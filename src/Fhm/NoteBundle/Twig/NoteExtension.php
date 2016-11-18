<?php
namespace Fhm\NoteBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Intl\Intl;

class NoteExtension extends \Twig_Extension
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
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('noteCount', array($this, 'getCount')),
            new \Twig_SimpleFilter('note', array($this, 'getNote'))
        );
    }

    /**
     * @param $document
     * @param $value
     *
     * @return mixed
     */
    public function getCount($document, $value)
    {
        return $this->container->get('fhm_note')->count($document, $value);
    }

    /**
     * @param $document
     * @param $instance
     *
     * @return array|int
     */
    public function getNote($document, $instance)
    {
        return $this->container->get('templating')->render
        (
            '::FhmNote/Template/note.html.twig',
            array
            (
                'param_maximum' => $this->container->getParameter('fhm_note')['maximum'],
                'document'      => $document,
                'note'          => $document ? $document->getNote() : 0,
                'instance'      => $instance
            )
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'note_extension';
    }
}
