<?php
namespace Fhm\MediaBundle\Controller\Tag;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\MediaBundle\Document\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/mediatag")
 */
class ApiController extends FhmController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Fhm', 'Media', 'media_tag', 'MediaTag');
        $this->translation = array('FhmMediaBundle', 'media.tag');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_media_tag"
     * )
     * @Template("::FhmMedia/Api/Tag/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_media_tag_autocomplete"
     * )
     * @Template("::FhmMedia/Api/Tag/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}