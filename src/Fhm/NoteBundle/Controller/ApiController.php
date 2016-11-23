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
 * @Route("/api/note", service="fhm_note_controller_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Note', 'note');
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
     *      path="/historic",
     *      name="fhm_api_note_historic"
     * )
     * @Template("::FhmNote/Api/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_note_historic_copy",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicCopyAction(Request $request, $id)
    {
        return parent::historicCopyAction($request, $id);
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
        $instance = $this->fhm_tools->instanceData();
        $document = $this->fhm_tools->dmRepository(ucfirst($source) . ucfirst($object) . 'Bundle:' . ucfirst($object))->find($id);

        return array(
            'view_star'          => $star,
            'view_average'       => $average,
            'view_comment'       => $comment,
            'view_comment_modal' => $comment_modal,
            'view_add'           => $add,
            'view_edit'          => $edit,
            'param_maximum'      => $this->fhm_tools->getParameters('maximum', 'fhm_note'),
            'param_anonymous'    => $this->fhm_tools->getParameters('anonymous', 'fhm_note'),
            'param_multiple'     => $this->fhm_tools->getParameters('multiple', 'fhm_note'),
            'param_edit'         => $this->fhm_tools->getParameters('edit', 'fhm_note'),
            'param_delete'       => $this->fhm_tools->getParameters('delete', 'fhm_note'),
            'param_default'      => $this->fhm_tools->getParameters('default', 'fhm_note'),
            'object'             => $object,
            'source'             => $source,
            'document'           => $document,
            'user'               => $this->fhm_tools->dmRepository()->getByUserAndObject($this->getUser(), $document),
            'average'            => $document ? $this->get('fhm_note')->average($document->getId()) : 0,
            'notes'              => $this->fhm_tools->dmRepository()->getByObject($id),
            'instance'           => $instance
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
        $data   = $request->get('FhmNote');
        $value  = $data['value'] > $this->fhm_tools->getParameters('maximum', 'fhm_note') ? $this->fhm_tools->getParameters('maximum', 'fhm_note') : ($data['value'] < 0 ? 0 : $data['value']);
        $bundle = ucfirst($data['source']) . ucfirst($data['object']) . 'Bundle:' . ucfirst($data['object']);
        $object = $this->fhm_tools->dmRepository($bundle)->find($id);
        // ERROR - unknown
        if($object == "")
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        // User connected
        elseif($this->getUser())
        {
            $document = $this->fhm_tools->dmRepository()->getByUserAndObject($this->getUser(), $object);
            if(!$document || $this->fhm_tools->getParameters('multiple', 'fhm_note'))
            {
                $note = new \Fhm\NoteBundle\Document\Note();
                $note->setName('[' . ucfirst($data['object']) . '] ' . $object->getName());
                $note->setDescription($object->getName() . ',' . $object->getAlias() . ',' . $object->getId());
                $note->setUser($this->getUser());
                $note->setParent($object);
                $note->setNote($value);
                $note->setContent($data['content']);
                $note->setDate(new \DateTime());
                $this->fhm_tools->dmPersist($note);
            }
        }
        // Anonymous
        elseif($this->fhm_tools->getParameter('anonymous', 'fhm_note'))
        {
            $note = new \Fhm\NoteBundle\Document\Note();
            $note->setName('[' . ucfirst($data['object']) . '] ' . $object->getName());
            $note->setDescription($object->getName() . ',' . $object->getAlias() . ',' . $object->getId());
            $note->setUser(null);
            $note->setParent($object);
            $note->setNote($value);
            $note->setContent($data['content']);
            $note->setDate(new \DateTime());
            $this->fhm_tools->dmPersist($note);
        }
        // ERROR - forbidden
        else
        {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }

        return $this->redirect($this->fhm_tools->getLastRoute());
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
        $data     = $request->get('FhmNote');
        $value    = $data['value'] > $this->fhm_tools->getParameters('maximum', 'fhm_note') ? $this->fhm_tools->getParameters('maximum', 'fhm_note') : ($data['value'] < 0 ? 0 : $data['value']);
        $document = $this->fhm_tools->dmRepository()->find($id);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        // ERROR - forbidden
        if(!$this->fhm_tools->getParameters('edit', 'fhm_note') || $document->getUser() != $this->getUser())
        {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        $document->setNote($value);
        $document->setContent($data['content']);
        $this->fhm_tools->dmPersist($document);

        return $this->redirect($this->fhm_tools->getLastRoute());
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
        $data     = $request->get('FhmNote');
        $value    = $data['value'] > $this->fhm_tools->getParameters('maximum', 'fhm_note') ? $this->fhm_tools->getParameters('maximum', 'fhm_note') : ($data['value'] < 0 ? 0 : $data['value']);
        $document = $this->fhm_tools->dmRepository()->find($id);
        // ERROR - unknown
        if($document == "")
        {
            throw $this->createNotFoundException($this->fhm_tools->trans('.error.unknown'));
        }
        // ERROR - forbidden
        if(!$this->fhm_tools->getParameters('delete', 'fhm_note') || $document->getUser() != $this->getUser())
        {
            throw new HttpException(403, $this->fhm_tools->trans('.error.forbidden'));
        }
        $document->setDelete(true);
        $this->fhm_tools->dmPersist($document);

        return $this->redirect($this->fhm_tools->getLastRoute());
    }
}