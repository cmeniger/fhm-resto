<?php
namespace Fhm\PartnerBundle\Controller\Group;

use Fhm\FhmBundle\Controller\RefFrontController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\PartnerBundle\Document\Partner;
use Fhm\PartnerBundle\Document\PartnerGroup;
use Fhm\PartnerBundle\Form\Type\Front\Group\UpdateType;
use Fhm\PartnerBundle\Form\Type\Front\Group\CreateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/partnergroup")
 * ------------------------------------------
 * Class FrontController
 * @package Fhm\PartnerBundle\Controller\Group
 */
class FrontController extends FhmController
{
    /**
     * AdminController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmPartnerBundle:PartnerGroup",
        $source = "fhm",
        $domain = "FhmPartnerBundle",
        $translation = "partner.group",
        $document = PartnerGroup::class,
        $route = 'partner_group'
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
     *      name="fhm_partner_group"
     * )
     * @Template("::FhmPartner/Front/Group/index.html.twig")
     */
    public function indexAction()
    {
        $response = parent::indexAction();
        foreach ($response['documents'] as $key => $document) {
            $response['documents'][$key]->documents = $this->get('fhm_tools')->dmRepository(
                "FhmPartnerBundle:Partner"
            )->getPartnerByGroupAll($document);
        }

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/detail/{id}",
     *      name="fhm_partner_group_detail",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Front/Group/detail.html.twig")
     */
    public function detailAction($id)
    {
        $response = parent::detailAction($id);
        $document = $response['document'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->get($form->getName()));
        $dataSearch = $form->getData();
        $documents = $this->get('fhm_tools')->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex(
            $document,
            $dataSearch['search']
        );

        return array_merge(
            $response,
            array(
                'documents' => $documents,
                'form' => $form->createView(),
            )
        );
    }


    /**
     * @Route
     * (
     *      path="/template/brief/{id}",
     *      name="fhm_partner_group_template_brief",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Template/brief.html.twig")
     */
    public function templateBriefAction($id)
    {
        $response = parent::detailAction($id);
        $document = $response['document'];
        $documents = $this->get('fhm_tools')->dmRepository("FhmPartnerBundle:Partner")->getPartnerByGroupIndex(
            $document,
            ''
        );

        return array_merge(
            $response,
            array(
                'documents' => $documents,
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/{id}",
     *      name="fhm_partner_group_lite",
     *      requirements={"id"=".+"}
     * )
     * @Template("::FhmPartner/Front/Group/detail.html.twig")
     */
    public function liteAction($id)
    {
        return $this->detailAction($id);
    }
}