<?php
namespace Fhm\NewsBundle\Controller;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NewsBundle\Document\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/news")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmNewsBundle:News";
        self::$source = "fhm";
        self::$domain = "FhmMenuBundle";
        self::$translation = "news";
        self::$document = new News();
        self::$class = get_class(self::$document);
        self::$route = 'news';
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_news"
     * )
     * @Template("::FhmNews/Api/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_news_autocomplete"
     * )
     * @Template("::FhmNews/Api/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/detail/{template}/{rows}/{pagination}/{id}",
     *      name="fhm_api_news_detail",
     *      requirements={"id"=".+", "rows"="\d*", "pagination"="[0-1]"},
     *      defaults={"id"=null, "rows"=null, "pagination"=1}
     * )
     * @Template("::FhmNews/Api/detail.html.twig")
     */
    public function detailAction($template, $id, $rows, $pagination)
    {
        $document = "";
        // News
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
                    $this->trans('news.group.error.unknown', array(), 'FhmNewsBundle')
                );
            } // ERROR - Forbidden
            elseif (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') && ($document->getDelete(
                    ) || !$document->getActive())
            ) {
                throw new HttpException(
                    403,
                    $this->trans('news.group.error.forbidden', array(), 'FhmNewsBundle')
                );
            }
        } else {
            // Group
            if ($id) {
                $document = $this->get('fhm_tools')->dmRepository("FhmNewsBundle:NewsGroup")->getById($id);
                $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(
                    "FhmNewsBundle:NewsGroup"
                )->getByAlias($id);
                $document = ($document) ? $document : $this->get('fhm_tools')->dmRepository(
                    "FhmNewsBundle:NewsGroup"
                )->getByName($id);
                // ERROR - unknown
                if ($document == "") {
                    throw $this->createNotFoundException(
                        $this->trans('news.group.error.unknown', array(), 'FhmNewsBundle')
                    );
                } // ERROR - Forbidden
                elseif (!$this->get('security.authorization_checker')->isGranted(
                        'ROLE_SUPER_ADMIN'
                    ) && ($document->getDelete() || !$document->getActive())
                ) {
                    throw new HttpException(
                        403,
                        $this->trans('news.group.error.forbidden', array(), 'FhmNewsBundle')
                    );
                }
            }
            // News
            $classType = '\Fhm\FhmBundle\Form\Type\Front\SearchType';
            $form = $this->createForm($classType);
            $form->setData($this->get('request_stack')->getCurrentRequest()->get($form->getName()));
            $dataSearch = $form->getData();
            $documents = $this->get('fhm_tools')->dmRepository(self::$repository)->getNewsByGroupIndex(
                $document,
                $dataSearch['search']
            );
        }

        return new Response(
            $this->renderView(
                "::FhmNews/Template/".$template.".html.twig",
                array(
                    'document' => $document,
                    'documents' => $documents,
                    'form' => $form ? $form->createView() : $form,
                )
            )
        );
    }
}