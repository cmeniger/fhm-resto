<?php
namespace Fhm\CardBundle\Controller\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\CardBundle\Form\Type\Api\Product\UpdateType;
use Fhm\CardBundle\Form\Type\Api\Product\CreateType;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Fhm\FhmBundle\Form\Handler\Admin\UpdateHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api/cardproduct")
 * -----------------------------------------
 * Class ApiController
 * @package Fhm\CardBundle\Controller\Product
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmCardBundle:CardProduct";
        self::$source = "fhm";
        self::$domain = "FhmCardBundle";
        self::$translation = "card.product";
        self::$route = "card_product";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
        self::$form->updateHandler = UpdateHandler::class;
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
     *      path="/embed/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_product_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idCategory, $template)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        // ERROR - unknown
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), self::$domain)
            );
        }
        if ($category == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Product/".$template.".html.twig",
                array(
                    "card" => $card,
                    "category" => $category,
                    "products" => $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardProduct')->getByCategory(
                        $card,
                        $category
                    ),
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($id);
        $this->__authorized($card);
        $categories = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->getByCardAll(
            $card
        );
        $trees = $this->__tree($card, $categories);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/product.html.twig",
                array(
                    "card" => $card,
                    "products" => $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardProduct')->setSort(
                        'alias'
                    )->getByCardAll(
                        $card
                    ),
                    "trees" => $trees,
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($id);
        $this->__authorized($card);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/product.search.html.twig",
                array(
                    "card" => $card,
                    "products" => $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardProduct')->setSort(
                        'alias'
                    )->getByCardSearch(
                        $card,
                        $request->get('search')
                    ),
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->__authorized($card);
        $list = json_decode($request->get('list'));
        $order = 1;
        foreach ($list as $obj) {
            $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($obj->id);
            $object->setOrder($order);
            $this->get('fhm_tools')->dmPersist($object);
            $order++;
        }

        return $this->__refresh($card);
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $this->__authorized($card);
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object = new self::$class;
        if ($category) {
            $object->addCategory($category);
        }
        $form = $this->createForm(
            self::$form->createType,
            $object,
            ['data_class' => self::$class, 'object_manager' => $this->get('fhm.object.manager')]
        );
        $handler = new self::$form->createHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $object->setCard($card);
            $this->get('fhm_tools')->dmPersist($object);

            return $this->__refresh($card);
        }

        return array(
            'card' => $card,
            'category' => $category,
            'form' => $form->createView(),
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idProduct);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $form = $this->createForm(
            self::$form->updateType,
            $object,
            ['data_class' => self::$class, 'object_manager' => $this->get('fhm.object.manager')]
        );
        $handler = new self::$form->updateHandler($form, $request);
        $process = $handler->process();
        if ($process) {
            $this->get('fhm_tools')->dmPersist($object);

            return $this->__refresh($card);
        }

        return array(
            'card' => $card,
            'object' => $object,
            'form' => $form->createView(),
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idProduct);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        $object->setActive(true);
        $this->get('fhm_tools')->dmPersist($object);

        return $this->__refresh($card);
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idProduct);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        $object->setActive(false);
        $this->get('fhm_tools')->dmPersist($object);

        return $this->__refresh($card);
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idProduct);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        // Delete
        if ($object->getDelete()) {
            $this->get('fhm_tools')->dmRemove($object);
        } else {
            $object->setDelete(true);
            $this->get('fhm_tools')->dmPersist($object);
        }

        return $this->__refresh($card);
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
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idProduct);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        // Undelete
        $object->setDelete(false);
        $this->get('fhm_tools')->dmPersist($object);

        return $this->__refresh($card);
    }

    /**
     * @param      $card
     * @param      $categories
     * @param null $use
     *
     * @return array
     */
    private function __tree($card, $categories, &$use = null)
    {
        $use = $use ? $use : new ArrayCollection();
        $tree = array();
        foreach ($categories as $category) {
            if (!$use->contains($category)) {
                $use->add($category);
                $sons = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->getSonsAll(
                    $card,
                    $category
                );
                $tree[] = array(
                    'category' => $category,
                    'products' => $this->get('fhm_tools')->dmRepository(self::$repository)->getByCategoryAll(
                        $card,
                        $category
                    ),
                );
                if ($sons) {
                    $tree = array_merge($tree, $this->__tree($card, $sons, $use));
                }
            }
        }

        return $tree;
    }

    /**
     * @param $card
     *
     * @return JsonResponse
     * @throws \Exception
     */
    private function __refresh($card)
    {
        $categories = $this->get('fhm_tools')->dmRepository('FhmCardBundle:CardCategory')->getByCardAll(
            $card
        );
        $trees = $this->__tree($card, $categories);
        $response = new JsonResponse();
        $response->setData(
            array(
                'status' => 200,
                'html' => $this->renderView(
                    "::FhmCard/Template/Editor/product.html.twig",
                    array(
                        "card" => $card,
                        "products" => $this->get('fhm_tools')->dmRepository(self::$repository)->setSort(
                            'alias'
                        )->getByCardAll(
                            $card
                        ),
                        "trees" => $trees,
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
    protected function __authorized($card)
    {
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), self::$domain)
            );
        }
        if ($card->getParent() && method_exists($card->getParent(), 'hasModerator')) {
            if (!$this->getUser()->isSuperAdmin() && !$this->getUser()->hasRole('ROLE_ADMIN') && !$card->getParent(
                )->hasModerator($this->getUser())
            ) {
                throw new HttpException(
                    403,
                    $this->get('translator')->trans(
                        self::$translation.'.error.forbidden',
                        array(),
                        self::$domain
                    )
                );
            }
        }

        return $this;
    }
}