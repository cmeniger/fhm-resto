<?php
namespace Fhm\GalleryBundle\Controller\Item;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\GalleryBundle\Document\Gallery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/gallery/item", service="fhm_gallery_controller_item_api")
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
        parent::__construct('Fhm', 'Gallery', 'gallery_item', 'GalleryItem');
        $this->translation = array('FhmGalleryBundle', 'gallery.item');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_gallery_item"
     * )
     * @Template("::FhmGallery/Api/Item/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete/",
     *      name="fhm_api_gallery_item_autocomplete"
     * )
     * @Template("::FhmGallery/Api/Item/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }
}