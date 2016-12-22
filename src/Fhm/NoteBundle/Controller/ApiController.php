<?php
namespace Fhm\NoteBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NoteBundle\Document\Note;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/note")
 * ----------------------------------
 * Class ApiController
 * @package Fhm\NoteBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNoteBundle:Note";
        self::$source = "fhm";
        self::$domain = "FhmNoteBundle";
        self::$translation = "note";
        self::$class = Note::class;
        self::$route = "note";
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_note"
     * )
     * @Template("::FhmNote/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_note_autocomplete"
     * )
     * @Template("::FhmNote/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/embed/{id}/{object}/{source}/{star}/{average}/{comment}/{comment_modal}/{add}/{edit}",
     *      name="fhm_api_note_embed",
     *      requirements={"id"="[a-z0-9]*"},
     *      defaults={"source"="fhm", "star"=1, "average"=1, "comment"=1, "comment_modal"=1, "add"=1, "edit"=1}
     * )
     * @Template("::FhmNote/Template/embed.html.twig")
     */
    public function embedAction($id, $object, $source, $star, $average, $comment, $comment_modal, $add, $edit)
    {
        $document = $this->get('fhm_tools')->dmRepository(
            ucfirst($source).ucfirst($object).'Bundle:'.ucfirst($object)
        )->find(
            $id
        );

        return array(
            'view_star' => $star,
            'view_average' => $average,
            'view_comment' => $comment,
            'view_comment_modal' => $comment_modal,
            'view_add' => $add,
            'view_edit' => $edit,
            'param_maximum' => $this->get('fhm_tools')->getParameters('maximum', 'fhm_note'),
            'param_anonymous' => $this->get('fhm_tools')->getParameters('anonymous', 'fhm_note'),
            'param_multiple' => $this->get('fhm_tools')->getParameters('multiple', 'fhm_note'),
            'param_edit' => $this->get('fhm_tools')->getParameters('edit', 'fhm_note'),
            'param_delete' => $this->get('fhm_tools')->getParameters('delete', 'fhm_note'),
            'param_default' => $this->get('fhm_tools')->getParameters('default', 'fhm_note'),
            'object' => $object,
            'source' => $source,
            'document' => $document,
            'user' => $this->get('fhm_tools')->dmRepository(self::$repository)->getByUserAndObject(
                $this->getUser(),
                $document
            ),
            'average' => $document ? $this->get('fhm_note')->average($document->getId()) : 0,
            'notes' => $this->get('fhm_tools')->dmRepository(self::$repository)->getByObject($id),
        );
    }

    /**
     * @Route
     * (
     *      path="/add/{id}",
     *      name="fhm_api_note_add",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function addAction(Request $request, $id)
    {
        $data = $request->get('FhmNote');
        $value = $data['value'] > $this->get('fhm_tools')->getParameters(
            'maximum',
            'fhm_note'
        ) ? $this->get('fhm_tools')->getParameters('maximum', 'fhm_note') : ($data['value'] < 0 ? 0 : $data['value']);
        $bundle = ucfirst($data['source']).ucfirst($data['object']).'Bundle:'.ucfirst($data['object']);
        $object = $this->get('fhm_tools')->dmRepository($bundle)->find($id);
        // ERROR - unknown
        if ($object == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        } // User connected
        elseif ($this->getUser()) {
            $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getByUserAndObject(
                $this->getUser(),
                $object
            );
            if (!$document || $this->get('fhm_tools')->getParameters('multiple', 'fhm_note')) {
                $note = new \Fhm\NoteBundle\Document\Note();
                $note->setName('['.ucfirst($data['object']).'] '.$object->getName());
                $note->setDescription($object->getName().','.$object->getAlias().','.$object->getId());
                $note->setUser($this->getUser());
                $note->setParent($object);
                $note->setNote($value);
                $note->setContent($data['content']);
                $note->setDate(new \DateTime());
                $this->get('fhm_tools')->dmPersist($note);
            }
        } // Anonymous
        elseif ($this->get('fhm_tools')->getParameters('anonymous', 'fhm_note')) {
            $note = new \Fhm\NoteBundle\Document\Note();
            $note->setName('['.ucfirst($data['object']).'] '.$object->getName());
            $note->setDescription($object->getName().','.$object->getAlias().','.$object->getId());
            $note->setUser(null);
            $note->setParent($object);
            $note->setNote($value);
            $note->setContent($data['content']);
            $note->setDate(new \DateTime());
            $this->get('fhm_tools')->dmPersist($note);
        } // ERROR - forbidden
        else {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/edit/{id}",
     *      name="fhm_api_note_edit",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function editAction(Request $request, $id)
    {
        $data = $request->get('FhmNote');
        $value = $data['value'] > $this->get('fhm_tools')->getParameters(
            'maximum',
            'fhm_note'
        ) ? $this->get('fhm_tools')->getParameters('maximum', 'fhm_note') : ($data['value'] < 0 ? 0 : $data['value']);
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        // ERROR - forbidden
        if (!$this->get('fhm_tools')->getParameters('edit', 'fhm_note') || $document->getUser() != $this->getUser()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        $document->setNote($value);
        $document->setContent($data['content']);
        $this->get('fhm_tools')->dmPersist($document);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_api_note_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction(Request $request, $id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - unknown
        if ($document == "") {
            throw $this->createNotFoundException($this->trans(self::$translation.'.error.unknown'));
        }
        // ERROR - forbidden
        if (!$this->get('fhm_tools')->getParameters('delete', 'fhm_note') || $document->getUser() != $this->getUser()) {
            throw new HttpException(403, $this->trans(self::$translation.'.error.forbidden'));
        }
        $document->setDelete(true);
        $this->get('fhm_tools')->dmPersist($document);

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }
}