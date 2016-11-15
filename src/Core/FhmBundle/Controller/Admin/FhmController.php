<?php

/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 15/11/16
 * Time: 10:16
 */

namespace Core\FhmBundle\Controller\Admin;

use Core\FhmBundle\Document\Fhm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * The base Controller to handle the backend.
 *
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 *
 * @author Fhm Team
 */
class FhmController extends Controller
{
    /**
     * Index backend page .
     *
     * This controller description...TODO.
     *
     * @Route("/", name="admin_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('admin/core/index.html.twig');
    }

}
