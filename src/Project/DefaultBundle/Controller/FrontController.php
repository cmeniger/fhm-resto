<?php
namespace Project\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/", service="project_default_controller_front")
 */
class FrontController extends Controller
{
    /**
     * @Route
     * (
     *      path="/",
     *      name="project_home"
     * )
     * @Template("::ProjectDefault/Front/home.html.twig")
     */
    public function homeAction()
    {

        return $this->get($this->getParameter("fhm_fhm")["grouping"])->loadGrouping();
    }

    /**
     * @Route
     * (
     *      path="/template/{name}",
     *      name="project_template"
     * )
     */
    public function templateAction($name)
    {
        // Response HTTP Cache
        $response = $this->get('fhm_cache')->getResponseCache();
        $template = ($this->get('templating')->exists('::ProjectDefault/Template/' . $name . '.html.twig')) ?
            '::ProjectDefault/Template/' . $name . '.html.twig' :
            '::ProjectDefault/Template/default.html.twig';
        $date     = new \DateTime();
        $response->setLastModified($date);

        return $this->render(
            $template,
            array(
                'instance' => $this->get('fhm_tools')->instanceData()
            ),
            $response
        );
    }
}