<?php
namespace Fhm\CardBundle\Controller\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\CardBundle\Form\Type\Api\Product\UpdateType;
use Fhm\CardBundle\Form\Type\Api\Product\CreateType;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\CardBundle\Document\CardProduct;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/cardproduct", service="fhm_card_controller_product_api")
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->setFhmTools($tools);
        parent::__construct('Fhm', 'Card', 'card_product', 'CardProduct');
        $this->form->type->create = CreateType::class;
        $this->form->type->update = UpdateType::class;
        $this->translation = array('FhmCardBundle', 'card.product');
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_card_product"
     * )
     * @Template("::FhmCard/Api/Product/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_card_product_autocomplete"
     * )
     * @Template("::FhmCard/Api/Product/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic",
     *      name="fhm_api_card_product_historic"
     * )
     * @Template("::FhmCard/Api/Product/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_card_product_historic_copy",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function historicCopyAction(Request $request, $id)
    {
        return parent::historicCopyAction($request, $id);
    }

    /**
     * @Route
     * (
     *      path="/embed/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_product_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idCategory, $template)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        // ERROR - unknown
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), $this->translation[0])
            );
        }
        if ($category == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans($this->translation[1].'.error.unknown', array(), $this->translation[0])
            );
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Product/".$template.".html.twig",
                array(
                    "card" => $card,
                    "category" => $category,
                    "products" => $this->fhm_tools->dmRepository()->getByCategory(
                        $card,
                        $category,
                        $instance->grouping->filtered
                    ),
                    "instance" => $instance,
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{id}",
     *      name="fhm_api_card_product_editor",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function editorAction(Request $request, $id)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($id);
        $instance = $this->fhm_tools->instanceData($card);
        $this->_authorized($card);
        $categories = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getByCardAll(
            $card,
            $instance->grouping->filtered
        );
        $trees = $this->_tree($card, $categories, $instance);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/product.html.twig",
                array(
                    "card" => $card,
                    "products" => $this->fhm_tools->dmRepository()->setSort('alias')->getByCardAll(
                        $card,
                        $instance->grouping->filtered
                    ),
                    "trees" => $trees,
                    "instance" => $instance,
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/search/{id}",
     *      name="fhm_api_card_product_search",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function searchAction(Request $request, $id)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($id);
        $instance = $this->fhm_tools->instanceData($card);
        $this->_authorized($card);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/product.search.html.twig",
                array(
                    "card" => $card,
                    "products" => $this->fhm_tools->dmRepository()->setSort('alias')->getByCardSearch(
                        $card,
                        $request->get('search'),
                        $instance->grouping->filtered
                    ),
                    "instance" => $instance,
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/sort/{idCard}/{idCategory}",
     *      name="fhm_api_card_product_sort",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     * )
     */
    public function sortAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        $list = json_decode($request->get('list'));
        $order = 1;
        foreach ($list as $obj) {
            $document = $this->fhm_tools->dmRepository()->find($obj->id);
            $document->setOrder($order);
            $this->fhm_tools->dmPersist($document);
            $order++;
        }

        return $this->_refresh($card, $instance);
    }

    /**
     * @Route
     * (
     *      path="/create/{idCard}/{idCategory}",
     *      name="fhm_api_card_product_create",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"idCategory"="0"}
     * )
     * @Template("::FhmCard/Template/Product/create.html.twig")
     */
    public function createAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        $document = $this->document;
        if ($category) {
            $document->addCategory($category);
        }
        $classType = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form = $this->createForm($classType, $document);
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setCard($card);
            $this->fhm_tools->dmPersist($document);

            return $this->_refresh($card, $instance);
        }

        return array(
            'card' => $card,
            'category' => $category,
            'form' => $form->createView(),
            'instance' => $instance,
        );
    }

    /**
     * @Route
     * (
     *      path="/update/{idCard}/{idProduct}",
     *      name="fhm_api_card_product_update",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Template/Product/update.html.twig")
     */
    public function updateAction(Request $request, $idCard, $idProduct)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idProduct);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans($this->translation[1].'.error.unknown', array(), $this->translation[0])
            );
        }
        $classType = $this->form->type->update;
        $classHandler = $this->form->handler->update;
        $form = $this->createForm($classType, $document);
        $handler = new $classHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->fhm_tools->dmPersist($document);

            return $this->_refresh($card, $instance);
        }

        return array(
            'card' => $card,
            'document' => $document,
            'form' => $form->createView(),
            'instance' => $instance,
        );
    }

    /**
     * @Route
     * (
     *      path="/activate/{idCard}/{idProduct}",
     *      name="fhm_api_card_product_activate",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"}
     * )
     */
    public function activateAction(Request $request, $idCard, $idProduct)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idProduct);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans($this->translation[1].'.error.unknown', array(), $this->translation[0])
            );
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(true);
        $this->fhm_tools->dmPersist($document);

        return $this->_refresh($card, $instance);
    }

    /**
     * @Route
     * (
     *      path="/deactivate/{idCard}/{idProduct}",
     *      name="fhm_api_card_product_deactivate",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction(Request $request, $idCard, $idProduct)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idProduct);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans($this->translation[1].'.error.unknown', array(), $this->translation[0])
            );
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(false);
        $this->fhm_tools->dmPersist($document);

        return $this->_refresh($card, $instance);
    }

    /**
     * @Route
     * (
     *      path="/delete/{idCard}/{idProduct}",
     *      name="fhm_api_card_product_delete",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"}
     * )
     */
    public function deleteAction(Request $request, $idCard, $idProduct)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idProduct);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans($this->translation[1].'.error.unknown', array(), $this->translation[0])
            );
        }
        // Delete
        if ($document->getDelete()) {
            $this->fhm_tools->dmRemove($document);
        } else {
            $document->setUserUpdate($this->getUser());
            $document->setDelete(true);
            $this->fhm_tools->dmPersist($document);
        }

        return $this->_refresh($card, $instance);
    }

    /**
     * @Route
     * (
     *      path="/undelete/{idCard}/{idProduct}",
     *      name="fhm_api_card_product_undelete",
     *      requirements={"idCard"="[a-z0-9]*", "idProduct"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction(Request $request, $idCard, $idProduct)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idProduct);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if ($document == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans($this->translation[1].'.error.unknown', array(), $this->translation[0])
            );
        }
        // Undelete
        $document->setUserUpdate($this->getUser());
        $document->setDelete(false);
        $this->fhm_tools->dmPersist($document);

        return $this->_refresh($card, $instance);
    }

    /**
     * @param      $card
     * @param      $categories
     * @param      $instance
     * @param null $use
     *
     * @return array
     */
    private function _tree($card, $categories, $instance, &$use = null)
    {
        $use = $use ? $use : new ArrayCollection();
        $tree = array();
        foreach ($categories as $category) {
            if (!$use->contains($category)) {
                $use->add($category);
                $sons = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getSonsAll(
                    $card,
                    $category,
                    $instance->grouping->filtered
                );
                $tree[] = array(
                    'category' => $category,
                    'products' => $this->fhm_tools->dmRepository()->getByCategoryAll(
                        $card,
                        $category,
                        $instance->grouping->filtered
                    ),
                );
                if ($sons) {
                    $tree = array_merge($tree, $this->_tree($card, $sons, $instance, $use));
                }
            }
        }

        return $tree;
    }

    /**
     * @param $card
     * @param $instance
     *
     * @return JsonResponse
     * @throws \Exception
     */
    private function _refresh($card, $instance)
    {
        $categories = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getByCardAll(
            $card,
            $instance->grouping->filtered
        );
        $trees = $this->_tree($card, $categories, $instance);
        $response = new JsonResponse();
        $response->setData(
            array(
                'status' => 200,
                'html' => $this->renderView(
                    "::FhmCard/Template/Editor/product.html.twig",
                    array(
                        "card" => $card,
                        "products" => $this->fhm_tools->dmRepository()->setSort('alias')->getByCardAll(
                            $card,
                            $instance->grouping->filtered
                        ),
                        "trees" => $trees,
                        "instance" => $instance,
                    )
                ),
            )
        );

        return $response;
    }

    /**
     * User is authorized
     *
     * @param $card
     *
     * @return $this
     */
    protected function _authorized($card)
    {
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), $this->translation[0])
            );
        }
        if ($card->getParent() && method_exists($card->getParent(), 'hasModerator')) {
            if (!$this->getUser()->isSuperAdmin() && !$this->getUser()->hasRole('ROLE_ADMIN') && !$card->getParent(
                )->hasModerator($this->getUser())
            ) {
                throw new HttpException(
                    403,
                    $this->get('translator')->trans(
                        $this->translation[1].'.error.forbidden',
                        array(),
                        $this->translation[0]
                    )
                );
            }
        }

        return $this;
    }
}