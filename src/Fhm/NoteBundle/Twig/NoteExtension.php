<?php
namespace Fhm\NoteBundle\Twig;

/**
 * Class NoteExtension
 *
 * @package Fhm\NoteBundle\Twig
 */
class NoteExtension extends \Twig_Extension
{
    protected $template;
    protected $note;
    protected $config;

    /**
     * NoteExtension constructor.
     * @param \Fhm\NoteBundle\Services\Note $note
     * @param $rootDir
     */
    public function __construct(\Fhm\NoteBundle\Services\Note $note, $rootDir, $config)
    {
        $this->template = new \Twig_Environment(new \Twig_Loader_Filesystem($rootDir));
        $this->note = $note;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('noteCount', array($this, 'getCount')),
            new \Twig_SimpleFilter('note', array($this, 'getNote')),
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
        return $this->note->count($document, $value);
    }

    /**
     * @param $document
     * @param $instance
     *
     * @return array|int
     */
    public function getNote($document, $instance)
    {
        return $this->template->render(
            '::FhmNote/Template/note.html.twig',
            array(
                'param_maximum' => $this->config['maximum'],
                'document' => $document,
                'note' => $document ? $document->getNote() : 0,
                'instance' => $instance,
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
