<?php
namespace Fhm\NewsBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\NewsBundle\Document\NewsTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/newstag", service="fhm_news_controller_tag_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(\Fhm\FhmBundle\Services\Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'News', 'news_tag', 'NewsTag');
        $this->translation = array('FhmNewsBundle', 'news.tag');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_news_tag"
     * )
     * @Template("::FhmNews/Api/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_news_tag_autocomplete"
     * )
     * @Template("::FhmNews/Api/Tag/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
    * @Route
    * (
    *      path="/historic",
    *      name="fhm_api_news_tag_historic"
    * )
    * @Template("::FhmNews/Api/Tag/historic.html.twig")
    */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
    * @Route
    * (
    *      path="/historic/copy/{id}",
    *      name="fhm_api_news_tag_historic_copy",
    *      requirements={"id"="[a-z0-9]*"}
    * )
    */
    public function historicCopyAction(Request $request, $id)
    {
        return parent::historicCopyAction($request, $id);
    }
}