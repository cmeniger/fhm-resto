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
     * FrontController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmPartnerBundle:PartnerGroup";
        self::$source = "fhm";
        self::$domain = "FhmPartnerBundle";
        self::$translation = "partner.group";
        self::$class = PartnerGroup::class;
        self::$route = "partner_group";
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
        $document = $response['object'];
        $form = $this->createForm(SearchType::class);
        $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
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