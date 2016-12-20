<?php
namespace Fhm\ContactBundle\Controller;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\ContactBundle\Document\Contact;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/contact")
 * ----------------------------------------
 * Class FrontController
 * @package Fhm\ContactBundle\Controller
 */
class FrontController extends FhmController
{
    /**
     * FrontController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmContactBundle:Contact",
        $source = "fhm",
        $domain = "FhmContactBundle",
        $translation = "contact",
        $document = "Contact",
        $route = 'contact'
    ) {
        self::$repository = $repository;
        self::$source = $source;
        self::$domain = $domain;
        self::$translation = $translation;
        self::$document = new $document();
        self::$class = get_class(self::$document);
        self::$route = $route;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_contact"
     * )
     * @Template("::FhmContact/Front/index.html.twig")
     */
    public function indexAction()
    {
        $classType = SearchType::class;
        $form = $this->createForm($classType);
        $form->setData($this->get('request_stack')->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex(
            $dataSearch['search']
        );

        return array(
            'documents' => $documents,
            'form' => $form->createView(),
            'breadcrumbs' => array(
                array(
                    'link' => $this->getUrl('project_home'),
                    'text' => $this->trans('project.home.breadcrumb', array(), 'ProjectDefaultBundle'),
                ),
                array(
                    'link' => $this->getUrl(self::$source.'_'.self::$route),
                    'text' => $this->trans(self::$translation.'.front.index.breadcrumb'),
                    'current' => true,
                ),
            ),
        );
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_contact_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmContact/Front/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_contact_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmContact/Front/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}