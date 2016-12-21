<?php
namespace Fhm\PartnerBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Type\Admin\SearchType;
use Fhm\PartnerBundle\Document\Partner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/partner")
 * -------------------------------------
 * Class ApiController
 * @package Fhm\PartnerBundle\Controller
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     * @param string $repository
     * @param string $source
     * @param string $domain
     * @param string $translation
     * @param string $document
     * @param string $route
     */
    public function __construct(
        $repository = "FhmPartnerBundle:Partner",
        $source = "fhm",
        $domain = "FhmPartnerBundle",
        $translation = "partner",
        $document = Partner::class,
        $route = 'partner'
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
     *      name="fhm_api_partner"
     * )
     * @Template("::FhmPartner/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_partner_autocomplete"
     * )
     * @Template("::FhmPartner/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}/{id}",
     *      name="fhm_api_partner_detail",
     *      requirements={"id"=".+", "rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"id"=null, "rows"=null, "pagination"=1}
     * )
     * @Template("::FhmPartner/Api/detail.html.twig")
     */
    public function detailAction($template, $id, $rows, $pagination)
    {
        $document = "";
        // Partner
        if ($id && $template == 'full') {
            $document = $this->get('fhm_tools')->dmRepository(self::$repository)->getById($id);
            $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByAlias(
                $id
            );
            $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(self::$repository)->getByName(
                $id
            );
            $documents = '';
            $form = '';
            // ERROR - unknown
            if ($document == "") {
                throw $this->createNotFoundException(
                    $this->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle')
                );
            } // ERROR - Forbidden
            elseif (!$this->getUser()->hasRole('ROLE_ADMIN') && ($document->getDelete() || !$document->getActive())) {
                throw new HttpException(
                    403, $this->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle')
                );
            }

        } else {
            // Group
            if ($id) {
                $document = $this->get('fhm_tools')->dmRepository("FhmPartnerBundle:PartnerGroup")->getById($id);
                $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(
                    "FhmPartnerBundle:PartnerGroup"
                )->getByAlias($id);
                $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(
                    "FhmPartnerBundle:PartnerGroup"
                )->getByName($id);
                // ERROR - unknown
                if ($document == "") {
                    throw $this->createNotFoundException(
                        $this->trans('partner.group.error.unknown', array(), 'FhmPartnerBundle')
                    );
                } // ERROR - Forbidden
                elseif (!$this->getUser()->hasRole('ROLE_ADMIN') && ($document->getDelete() || !$document->getActive(
                        ))
                ) {
                    throw new HttpException(
                        403, $this->trans('partner.group.error.forbidden', array(), 'FhmPartnerBundle')
                    );
                }
            }
            // Partner
            $form = $this->createForm(SearchType::class, null);
            $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
            $dataSearch = $form->getData();
            $documents = $document ? $this->get('fhm_tools')->dmRepository(self::$repository)->getPartnerByGroupIndex(
                $document,
                $dataSearch['search']
            ) : $this->get('fhm_tools')->dmRepository(self::$repository)->getFrontIndex($dataSearch['search']);

        }

        return new Response(
            $this->renderView(
                "::FhmPartner/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'documents' => $documents,
                    'form' => $form ? $form->createView() : $form,
                )
            )
        );
    }
}