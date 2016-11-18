<?php
namespace Fhm\WorkflowBundle\Controller\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\WorkflowBundle\Document\WorkflowTask;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/workflowtask")
 */
class ApiController extends FhmController
{
    protected $trans_status;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Workflow', 'workflow_task', 'WorkflowTask');
        $this->translation = array('FhmWorkflowBundle', 'workflow.task');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_workflow_task"
     * )
     * @Template("::FhmWorkflow/Api/Task/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_workflow_task_autocomplete"
     * )
     * @Template("::FhmWorkflow/Api/Task/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/modal/expand/{wid}/{tid}",
     *      name="fhm_api_workflow_task_modal_expand",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/modal.expand.html.twig")
     */
    public function modalExpandAction($wid, $tid)
    {
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);

        return array(
            'document' => $document,
            'workflow' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/modal/comment/{wid}/{tid}",
     *      name="fhm_api_workflow_task_modal_comment",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/modal.comment.html.twig")
     */
    public function modalCommentAction($wid, $tid)
    {
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);

        return array(
            'document' => $document,
            'workflow' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/modal/media/{wid}/{tid}",
     *      name="fhm_api_workflow_task_modal_media",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/modal.media.html.twig")
     */
    public function modalMediaAction($wid, $tid)
    {
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);

        return array(
            'document' => $document,
            'workflow' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/add/media/{wid}/{tid}",
     *      name="fhm_api_workflow_task_add_media",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/modal.media.html.twig")
     */
    public function addMediaAction(Request $request, $wid, $tid)
    {
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);
        $fileData = array
        (
            'tmp_name' => isset($_FILES['file']) ? $_FILES['file']['tmp_name'] : $_FILES['media']['tmp_name']['file'],
            'name'     => isset($_FILES['file']) ? $_FILES['file']['name'] : $_FILES['media']['name']['file'],
            'type'     => isset($_FILES['file']) ? $_FILES['file']['type'] : $_FILES['media']['type']['file']
        );
        if($fileData['name']
            && $document->getAction()
            && $document->getAction()->getUploadCheck()
            && $document->getAction()->hasUserUpload($this->getUser())
        )
        {
            // Tag - Parent
            $tagParent = $this->dmRepository('FhmMediaBundle:MediaTag')->getByName('workflow');
            if($tagParent == "")
            {
                $tagParent = new \Fhm\MediaBundle\Document\MediaTag();
                $tagParent->setName('workflow');
                $tagParent->setActive(true);
                $tagParent->setPrivate(true);
                $this->dmPersist($tagParent);
            }
            // Tag - Workflow
            $tagWorkflow = $this->dmRepository('FhmMediaBundle:MediaTag')->getByName($workflow->getAlias());
            if($tagWorkflow == "")
            {
                $tagWorkflow = new \Fhm\MediaBundle\Document\MediaTag();
                $tagWorkflow->setName($workflow->getAlias());
                $tagWorkflow->setActive(true);
                $tagWorkflow->setPrivate(true);
                $tagWorkflow->setParent($tagParent);
                $this->dmPersist($tagWorkflow);
            }
            // Tag - Task
            $tagTask = $this->dmRepository('FhmMediaBundle:MediaTag')->getByName($document->getAlias());
            if($tagTask == "")
            {
                $tagTask = new \Fhm\MediaBundle\Document\MediaTag();
                $tagTask->setName($document->getAlias());
                $tagTask->setActive(true);
                $tagTask->setParent($tagWorkflow);
                $this->dmPersist($tagTask);
            }
            // Media
            $file  = new UploadedFile($fileData['tmp_name'], $fileData['name'], $fileData['type']);
            $tab   = explode('.', $fileData['name']);
            $name  = $tab[0];
            $media = new \Fhm\MediaBundle\Document\Media();
            $media->setName($name);
            $media->setFile($file);
            $media->setUserCreate($this->getUser());
            $media->setAlias($this->getAlias('', $name));
            $media->setActive(true);
            $media->addTag($tagWorkflow);
            $media->addTag($tagTask);
            $this->dmPersist($media);
            $this->get($this->getParameter('service', 'fhm_media'))->setDocument($media)->execute();
            // Log
            $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
            $log->setType(6);
            $log->setUserCreate($this->getUser());
            $log->setActive(true);
            $log->setTask($document);
            $log->setName($document->getName());
            $log->setDescription($this->_textMedia($fileData['name']));
            $this->dmPersist($log);
            // Task
            $document->addMedia($media);
            $document->addLog($log);
            $this->dmPersist($document);
            // Workflow
            if($workflow)
            {
                $workflow->sortUpdate();
                $this->dmPersist($workflow);
            }
            // Message
            $this->get('session')->getFlashBag()->add('workflow', $this->get('translator')->trans('workflow.events.comment', array(), $this->translation[0]));
        }
        else
        {
            $this->get('session')->getFlashBag()->add('workflow-error', $this->get('translator')->trans('workflow.events.error', array(), $this->translation[0]));
        }

        return array(
            'document' => $document,
            'workflow' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/add/comment/{wid}/{tid}",
     *      name="fhm_api_workflow_task_add_comment",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/modal.comment.html.twig")
     */
    public function addCommentAction(Request $request, $wid, $tid)
    {
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);
        if($request->get('comment')
            && $document->getAction()
            && $document->getAction()->getCommentCheck()
            && $document->getAction()->hasUserComment($this->getUser())
        )
        {
            // Comment
            $comment = new \Fhm\WorkflowBundle\Document\WorkflowComment();
            $comment->setUserCreate($this->getUser());
            $comment->setActive(true);
            $comment->setTask($document);
            $comment->setName($document->getName());
            $comment->setDescription($request->get('comment'));
            $this->dmPersist($comment);
            // Log
            $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
            $log->setType(5);
            $log->setUserCreate($this->getUser());
            $log->setActive(true);
            $log->setTask($document);
            $log->setName($document->getName());
            $log->setDescription($request->get('comment'));
            $this->dmPersist($log);
            // Task
            $document->addComment($comment);
            $document->addLog($log);
            $this->dmPersist($document);
            // Workflow
            if($workflow)
            {
                $workflow->sortUpdate();
                $this->dmPersist($workflow);
            }
            // Message
            $this->get('session')->getFlashBag()->add('workflow', $this->get('translator')->trans('workflow.events.comment', array(), $this->translation[0]));
        }
        else
        {
            $this->get('session')->getFlashBag()->add('workflow-error', $this->get('translator')->trans('workflow.events.error', array(), $this->translation[0]));
        }

        return array(
            'document' => $document,
            'workflow' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/action/validate/{wid}/{tid}",
     *      name="fhm_api_workflow_task_action_validate",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/workflow.html.twig")
     */
    public function actionValidateAction(Request $request, $wid, $tid)
    {
        $this->_init();
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);
        if(($document->getStatus() == 1 || ($document->getStatus() == 0 && $document->getParents()->count() == 0))
            && $document->getAction()
            && $document->getAction()->getValidateCheck()
            && $document->getAction()->hasUserValidate($this->getUser())
        )
        {
            // Task
            $document->setStatus(2);
            $this->dmPersist($document);
            // Sons
            foreach($document->getSons() as $son)
            {
                $before = $son->getStatus();
                $son->checkStatus();
                $this->dmPersist($son);
                $after = $son->getStatus();
                if($before != $after)
                {
                    $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
                    $log->setType(1);
                    $log->setUserCreate($this->getUser());
                    $log->setActive(true);
                    $log->setTask($son);
                    $log->setName($son->getName());
                    $log->setDescription($this->_textStatus($before, $after) . $document->getName() . ' : ' . $this->get('translator')->trans('workflow.events.validate', array(), $this->translation[0]));
                    $this->dmPersist($log);
                    $son->addLog($log);
                    $this->dmPersist($son);
                }
            }
            // Log
            $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
            $log->setType(2);
            $log->setUserCreate($this->getUser());
            $log->setActive(true);
            $log->setTask($document);
            $log->setName($document->getName());
            $log->setDescription($this->_textStatus(1, 2));
            $this->dmPersist($log);
            $document->addLog($log);
            $this->dmPersist($document);
            // Workflow
            if($workflow)
            {
                $workflow->sortUpdate();
                $this->dmPersist($workflow);
            }
            // Message
            $this->get('session')->getFlashBag()->add('workflow', $this->get('translator')->trans('workflow.events.validate', array(), $this->translation[0]));
        }
        else
        {
            $this->get('session')->getFlashBag()->add('workflow-error', $this->get('translator')->trans('workflow.events.error', array(), $this->translation[0]));
        }

        return array(
            'document' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/action/dismiss/{wid}/{tid}",
     *      name="fhm_api_workflow_task_action_dismiss",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/workflow.html.twig")
     */
    public function actionDismissAction(Request $request, $wid, $tid)
    {
        $this->_init();
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);
        if($document->getStatus() == 1
            && $document->getAction()
            && $document->getAction()->getDismissCheck()
            && $document->getAction()->hasUserDismiss($this->getUser())
        )
        {
            // Task
            $document->setStatus(0);
            $this->dmPersist($document);
            // Parents
            foreach($document->getParents() as $parent)
            {
                $parent->setStatus(1);
                $this->dmPersist($parent);
                $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
                $log->setType(1);
                $log->setUserCreate($this->getUser());
                $log->setActive(true);
                $log->setTask($parent);
                $log->setName($parent->getName());
                $log->setDescription($this->_textStatus(2, 1) . $document->getName() . ' : ' . $this->get('translator')->trans('workflow.events.dismiss', array(), $this->translation[0]));
                $this->dmPersist($log);
                $parent->addLog($log);
                $this->dmPersist($parent);
                foreach($parent->getSons() as $son)
                {
                    $before = $son->getStatus();
                    $son->checkStatus();
                    $this->dmPersist($son);
                    $after = $son->getStatus();
                    if($before != $after)
                    {
                        $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
                        $log->setType(1);
                        $log->setUserCreate($this->getUser());
                        $log->setActive(true);
                        $log->setTask($son);
                        $log->setName($son->getName());
                        $log->setDescription($this->_textStatus($before, $after) . $document->getName() . ' : ' . $this->get('translator')->trans('workflow.events.dismiss', array(), $this->translation[0]));
                        $this->dmPersist($log);
                        $son->addLog($log);
                        $this->dmPersist($son);
                    }
                }
            }
            // Log
            $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
            $log->setType(3);
            $log->setUserCreate($this->getUser());
            $log->setActive(true);
            $log->setTask($document);
            $log->setName($document->getName());
            $log->setDescription($this->_textStatus(1, 0));
            $this->dmPersist($log);
            $document->addLog($log);
            $this->dmPersist($document);
            // Workflow
            if($workflow)
            {
                $workflow->sortUpdate();
                $this->dmPersist($workflow);
            }
            // Message
            $this->get('session')->getFlashBag()->add('workflow-warning', $this->get('translator')->trans('workflow.events.dismiss', array(), $this->translation[0]));
        }
        else
        {
            $this->get('session')->getFlashBag()->add('workflow-error', $this->get('translator')->trans('workflow.events.error', array(), $this->translation[0]));
        }

        return array(
            'document' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/action/cancel/{wid}/{tid}",
     *      name="fhm_api_workflow_task_action_cancel",
     *      requirements={"wid"="[a-z0-9]*", "tid"="[a-z0-9]*"}
     * )
     * @Template("::FhmWorkflow/Template/workflow.html.twig")
     */
    public function actionCancelAction(Request $request, $wid, $tid)
    {
        $this->_init();
        $document = $this->dmRepository()->find($tid);
        $workflow = $this->dmRepository('FhmWorkflowBundle:Workflow')->find($wid);
        $instance = $this->instanceData($document);
        if($document->getStatus() == 1
            && $document->getAction()
            && $document->getAction()->getCancelCheck()
            && $document->getAction()->hasUserCancel($this->getUser())
        )
        {
            // Task
            $document->setStatus(3);
            $this->dmPersist($document);
            // Log
            $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
            $log->setType(4);
            $log->setUserCreate($this->getUser());
            $log->setActive(true);
            $log->setTask($document);
            $log->setName($document->getName());
            $log->setDescription($this->_textStatus(1, 3));
            $this->dmPersist($log);
            $document->addLog($log);
            $this->dmPersist($document);
            // Workflow
            if($workflow)
            {
                $workflow->sortUpdate();
                $this->dmPersist($workflow);
            }
            // Message
            $this->get('session')->getFlashBag()->add('workflow-error', $this->get('translator')->trans('workflow.events.cancel', array(), $this->translation[0]));
        }
        else
        {
            $this->get('session')->getFlashBag()->add('workflow-error', $this->get('translator')->trans('workflow.events.error', array(), $this->translation[0]));
        }

        return array(
            'document' => $workflow,
            'instance' => $instance
        );
    }

    /**
     * @Route
     * (
     *      path="/status/wait/{id}",
     *      name="fhm_api_workflow_task_status_wait",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function statusWait($id)
    {
        $this->_init();
        $document = $this->dmRepository()->find($id);
        if($document->getStatus() != 0
            && $document->getAction()
            && $document->getAction()->getDismissCheck()
            && $document->getAction()->hasUserDismiss($this->getUser())
        )
        {
            $this->_updateStatus($document, 0);
        }

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/status/run/{id}",
     *      name="fhm_api_workflow_task_status_run",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function statusRun($id)
    {
        $this->_init();
        $document = $this->dmRepository()->find($id);
        if($document->getStatus() != 1
            && $document->getAction()
            && $document->getAction()->getValidateCheck()
            && $document->getAction()->hasUserValidate($this->getUser())
        )
        {
            $this->_updateStatus($document, 1);
        }

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/status/finish/{id}",
     *      name="fhm_api_workflow_task_status_finish",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function statusFinish($id)
    {
        $this->_init();
        $document = $this->dmRepository()->find($id);
        if($document->getStatus() != 2
            && $document->getAction()
            && $document->getAction()->getValidateCheck()
            && $document->getAction()->hasUserValidate($this->getUser())
        )
        {
            $this->_updateStatus($document, 2);
        }

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @Route
     * (
     *      path="/status/cancel/{id}",
     *      name="fhm_api_workflow_task_status_cancel",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function statusCancel($id)
    {
        $this->_init();
        $document = $this->dmRepository()->find($id);
        if($document->getStatus() != 3
            && $document->getAction()
            && $document->getAction()->getCancelCheck()
            && $document->getAction()->hasUserCancel($this->getUser())
        )
        {
            $this->_updateStatus($document, 3);
        }

        return $this->redirect($this->getLastRoute());
    }

    /**
     * @return $this
     */
    private function _init()
    {
        $this->trans_status = array(
            '0' => $this->get('translator')->trans('workflow.status.wait', array(), $this->translation[0]),
            '1' => $this->get('translator')->trans('workflow.status.run', array(), $this->translation[0]),
            '2' => $this->get('translator')->trans('workflow.status.finish', array(), $this->translation[0]),
            '3' => $this->get('translator')->trans('workflow.status.cancel', array(), $this->translation[0]),
        );

        return $this;
    }

    /**
     * @param $before
     * @param $after
     *
     * @return string
     */
    private function _textStatus($before, $after)
    {
        return $this->get('translator')->trans('workflow.log.text.status', array('%before%' => $this->trans_status[$before], '%after%' => $this->trans_status[$after]), $this->translation[0]) . '<br>';
    }

    /**
     * @param $name
     *
     * @return string
     */
    private function _textMedia($name)
    {
        return $this->get('translator')->trans('workflow.log.text.media', array('%name%' => $name), $this->translation[0]) . '<br>';
    }

    /**
     * @return $this
     */
    private function _updateStatus($document, $status)
    {
        // Task
        $before = $document->getStatus();
        $document->setStatus($status);
        $this->dmPersist($document);
        // Log
        $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
        $log->setType(1);
        $log->setUserCreate($this->getUser());
        $log->setActive(true);
        $log->setTask($document);
        $log->setName($document->getName());
        $log->setDescription($this->_textStatus($before, $status) . $this->get('translator')->trans('workflow.events.status', array(), $this->translation[0]));
        $this->dmPersist($log);
        $document->addLog($log);
        $this->dmPersist($document);
        // Update
        $treats = new ArrayCollection();
        $treats->add($document);
        $this->_updateTasks($document, $document, $treats);
        $this->_updateWorkflows();

        return $this;
    }

    /**
     * @param $task
     *
     * @return $this
     */
    private function _updateTasks($task, $source, &$treats)
    {
        $statusParent = 0;
        $statusParent = $task->getStatus() == 0 ? 1 : $statusParent;
        $statusParent = $task->getStatus() == 1 ? 2 : $statusParent;
        $statusParent = $task->getStatus() == 2 ? 2 : $statusParent;
        $statusParent = $task->getStatus() == 3 ? 2 : $statusParent;
        $statusSon    = 0;
        $statusSon    = $task->getStatus() == 0 ? 0 : $statusSon;
        $statusSon    = $task->getStatus() == 1 ? 0 : $statusSon;
        $statusSon    = $task->getStatus() == 2 ? 1 : $statusSon;
        $statusSon    = $task->getStatus() == 3 ? 0 : $statusSon;
        $parentNew    = false;
        $sonNew       = false;
        // Sons
        foreach($task->getSons() as $son)
        {
            if($son->getStatus() == $statusSon && !$treats->contains($son))
            {
                $treats->add($son);
            }
            if(!$treats->contains($son))
            {
                $sonNew = true;
                // Task
                $before = $son->getStatus();
                $son->setStatus($statusSon);
                $this->dmPersist($son);
                // Log
                $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
                $log->setType(1);
                $log->setUserCreate($this->getUser());
                $log->setActive(true);
                $log->setTask($son);
                $log->setName($son->getName());
                $log->setDescription($this->_textStatus($before, $statusSon) . $task->getName() . ' : ' . $this->get('translator')->trans('workflow.events.status', array(), $this->translation[0]));
                $this->dmPersist($log);
                $son->addLog($log);
                $this->dmPersist($son);
                $treats->add($son);
            }
        }
        if($sonNew)
        {
            foreach($task->getSons() as $son)
            {
                if($son != $source)
                {
                    $this->_updateTasks($son, $source, $treats);
                }
            }
        }
        // Parents
        foreach($task->getParents() as $parent)
        {
            if($parent->getStatus() == $statusParent && !$treats->contains($parent))
            {
                $treats->add($parent);
            }
            if(!$treats->contains($parent))
            {
                $parentNew = true;
                // Task
                $before = $parent->getStatus();
                $parent->setStatus($statusParent);
                $this->dmPersist($parent);
                // Log
                $log = new \Fhm\WorkflowBundle\Document\WorkflowLog();
                $log->setType(1);
                $log->setUserCreate($this->getUser());
                $log->setActive(true);
                $log->setTask($parent);
                $log->setName($parent->getName());
                $log->setDescription($this->_textStatus($before, $statusParent) . $task->getName() . ' : ' . $this->get('translator')->trans('workflow.events.status', array(), $this->translation[0]));
                $this->dmPersist($log);
                $parent->addLog($log);
                $this->dmPersist($parent);
                $treats->add($parent);
            }
        }
        if($parentNew)
        {
            foreach($task->getParents() as $parent)
            {
                if($parent != $source)
                {
                    $this->_updateTasks($parent, $source, $treats);
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function _updateWorkflows()
    {
        $workflows = $this->dmRepository('FhmWorkflowBundle:Workflow')->getAllEnable();
        foreach($workflows as $workflow)
        {
            $workflow->sortUpdate();
            $this->dmPersist($workflow);
        }

        return $this;
    }
}