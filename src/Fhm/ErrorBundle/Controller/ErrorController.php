<?php
namespace Fhm\ErrorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class ErrorController extends Controller
{
    /**
     * @Route
     * (
     *      path="/{route}",
     *      name="fhm_error_index",
     *      requirements = {"route" = ".+"}
     * )
     * @Template
     */
    public function indexAction($route)
    {
        throw $this->createNotFoundException("La route n'existe pas.");
    }
}
