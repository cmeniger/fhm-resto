<?php
namespace Fhm\MailBundle\Controller;

use Fhm\FhmBundle\Controller\RefAdminController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\MailBundle\Form\Type\Admin\CreateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/mail")
 * ----------------------------------
 * Class AdminController
 * @package Fhm\MailBundle\Controller
 */
class AdminController extends FhmController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmMailBundle:Mail";
        self::$source = "fhm";
        self::$domain = "FhmMailBundle";
        self::$translation = "mail";
        self::$route = "mail";
        self::$form = new \stdClass();
        self::$form->createType = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_admin_mail"
     * )
     * @Template("::FhmMail/Admin/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/create",
     *      name="fhm_admin_mail_create"
     * )
     * @Template("::FhmMail/Admin/create.html.twig")
     */
    public function createAction(Request $request)
    {
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object = new self::$class;
        $form = $this->createForm(self::$form->createType, $object, array('data_class'=>self::$class));
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $data = $request->get($form->getName());
            $this->get('fhm_mail')->adminMessage(
                array(
                    'to' => $data['to'],
                    'object' => $data['object'],
                    'body' => $data['body'],
                )
            );
            // Message
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->trans('mail.admin.create.flash.ok')
            );

            return $this->redirect($this->getUrl('fhm_admin_mail'));
        }

        return array(
            'form' => $form->createView(),
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_admin_mail_detail",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     * @Template("::FhmMail/Admin/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_admin_mail_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_admin_mail_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        return parent::undeleteAction($id);
    }

    /**
     * @Route
     * (
     *      path="/model",
     *      name="fhm_admin_mail_model"
     * )
     * @Template("::FhmMail/Admin/model.html.twig")
     */
    public function modelAction(Request $request)
    {
        return array(
            'breadcrumbs' => $this->get('fhm_tools')->generateBreadcrumbs(
                array(
                    'domain' => self::$domain,
                    '_route' => $this->get('request_stack')->getCurrentRequest()->get('_route'),
                )
            ),
        );
    }
}