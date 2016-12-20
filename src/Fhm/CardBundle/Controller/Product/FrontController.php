<?php
namespace Fhm\CardBundle\Controller\Product;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\CardBundle\Form\Type\Front\Product\UpdateType;
use Fhm\CardBundle\Form\Type\Front\Product\CreateType;
use Fhm\CardBundle\Document\CardProduct;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Tests\Fixtures\ToBeProxyfiedDummy;

/**
 * @Route("/cardproduct")
 * -----------------------------------------
 * Class FrontController
 * @package Fhm\CardBundle\Controller\Product
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
        $repository = "FhmCardBundle:CardProduct",
        $source = "fhm",
        $domain = "FhmCardBundle",
        $translation = "card.product",
        $document = CardProduct::class,
        $route = "card_product"
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
     *      name="fhm_card_product"
     * )
     * @Template("::FhmCard/Front/Product/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_card_product_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Product/detail.html.twig")
     */
    public function detailAction($id)
    {
        return parent::detailAction($id);
    }

    /**
     * @Route
     * (
     *      path="/delete/{id}",
     *      name="fhm_card_product_delete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deleteAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        // Deactivate
        $document->setDelete(true);
        $this->get('fhm_tools')->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->get('translator')->trans(
                self::$translation.'.admin.activate.flash.ok',
                array(),
                self::$domain
            )
        );

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/undelete/{id}",
     *      name="fhm_card_product_undelete",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        // Undelete
        $document->setDelete(false);
        $this->get('fhm_tools')->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->get('translator')->trans(
                self::$translation.'.admin.undelete.flash.ok',
                array(),
                self::$domain
            )
        );

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }


    /**
     * @Route
     * (
     *      path="/activate/{id}",
     *      name="fhm_card_product_activate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function activateAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        // activate
        $document->setActive(true);
        $this->get('fhm_tools')->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->get('translator')->trans(
                self::$translation.'.admin.activate.flash.ok',
                array(),
                self::$domain
            )
        );

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{id}",
     *      name="fhm_card_product_deactivate",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction($id)
    {
        $document = $this->get('fhm_tools')->dmRepository(self::$repository)->find($id);
        // ERROR - Unknown
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        // Deactivate
        $document->setActive(false);
        $this->get('fhm_tools')->dmPersist($document);
        // Message
        $this->get('session')->getFlashBag()->add(
            'notice',
            $this->get('translator')->trans(
                self::$translation.'.admin.activate.flash.ok',
                array(),
                self::$domain
            )
        );

        return $this->redirect($this->get('fhm_tools')->getLastRoute($this->get('request_stack')->getCurrentRequest()));
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_card_product_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmCard/Front/Product/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}