<?php
namespace Fhm\CardBundle\Controller\Category;

use Fhm\CardBundle\Form\Type\Api\Category\CreateType;
use Fhm\CardBundle\Form\Type\Api\Category\UpdateType;
use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\FhmBundle\Form\Handler\Admin\CreateHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/api/cardcategory")
 * ------------------------------------------
 * Class ApiController
 * @package Fhm\CardBundle\Controller\Category
 */
class ApiController extends FhmController
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        self::$repository = "FhmCardBundle:CardCategory";
        self::$source = "fhm";
        self::$domain = "FhmCardBundle";
        self::$translation = "card.category";
        self::$route = "card_category";
        self::$form = new \stdClass();
        self::$form->createType    = CreateType::class;
        self::$form->createHandler = CreateHandler::class;
        self::$form->updateType    = UpdateType::class;
    }

    /**
     * @Route
     * (
     *      path="/",
     *      name="fhm_api_card_category"
     * )
     * @Template("::FhmCard/Api/Category/index.html.twig")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route
     * (
     *      path="/autocomplete",
     *      name="fhm_api_card_category_autocomplete"
     * )
     * @Template("::FhmCard/Api/Category/autocomplete.html.twig")
     */
    public function autocompleteAction(Request $request)
    {
        return parent::autocompleteAction($request);
    }

    /**
     * @Route
     * (
     *      path="/embed/{template}/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idCategory, $template)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
        // ERROR - unknown
        if ($card == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), self::$domain)
            );
        }
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Category/".$template.".html.twig",
                array(
                    "card" => $card,
                    "object" => $object,
                    "objects" => $this->get('fhm_tools')->dmRepository(self::$repository)->getSons($card, $object),
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/editor/{id}",
     *      name="fhm_api_card_category_editor",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function editorAction(Request $request, $id)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($id);
        $this->__authorized($card);
        $categories = $this->get('fhm_tools')->dmRepository(self::$repository)->getByCardAll($card);
        $tree = $this->__tree($card, $categories);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/category.html.twig",
                array(
                    "card" => $card,
                    "categories" => $categories,
                    "tree" => $tree,
                )
            )
        );
    }

    /**
     * @Route
     * (
     *      path="/sort/{id}",
     *      name="fhm_api_card_category_sort",
     *      requirements={"id"="[a-z0-9]*"}
     * )
     */
    public function sortAction(Request $request, $id)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($id);
        $this->__authorized($card);
        $list = json_decode($request->get('list'));
        $this->__treeSort($list);
        $response = new JsonResponse();
        $response->setData(
            array(
                'status' => 200,
            )
        );

        return $response;
    }

    /**
     * @Route
     * (
     *      path="/create/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_create",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"idCategory"="0"}
     * )
     * @Template("::FhmCard/Template/Category/create.html.twig")
     */
    public function createAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
        $this->__authorized($card);

        self::$class = $this->get('fhm.object.manager')->getCurrentModelName(self::$repository);
        $object = self::$class;
        if ($category) {
            $object->addParent($category);
        }
        $classType = CreateType::class;
        $classHandler = CreateHandler::class;
        $form = $this->createForm(new $classType, $object);
        $handler = new $classHandler($form, $request);
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
     *      path="/update/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_update",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"}
     * )
     * @Template("::FhmCard/Template/Category/update.html.twig")
     */
    public function updateAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        $classType = CreateType::class;
        $classHandler = CreateHandler::class;
        $form = $this->createForm(new $classType, $object);
        $handler = new $classHandler($form, $request);
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
     *      path="/activate/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_activate",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"}
     * )
     */
    public function activateAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
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
     *      path="/deactivate/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_deactivate",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"}
     * )
     */
    public function deactivateAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
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
     *      path="/delete/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_delete",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"}
     * )
     */
    public function deleteAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
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
     *      path="/undelete/{idCard}/{idCategory}",
     *      name="fhm_api_card_category_undelete",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"}
     * )
     */
    public function undeleteAction(Request $request, $idCard, $idCategory)
    {
        $card = $this->get('fhm_tools')->dmRepository('FhmCardBundle:Card')->find($idCard);
        $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($idCategory);
        $this->__authorized($card);
        if ($object == "") {
            throw $this->createNotFoundException(
                $this->get('translator')->trans(self::$translation.'.error.unknown', array(), self::$domain)
            );
        }
        $object->setDelete(false);
        $this->get('fhm_tools')->dmPersist($object);

        return $this->__refresh($card);
    }

    /**
     * @param $card
     * @param $categories
     *
     * @return array
     */
    private function __tree($card, $categories)
    {
        $tree = array();
        foreach ($categories as $category) {
            $sons = $this->get('fhm_tools')->dmRepository(self::$repository)->getSonsAll($card, $category);
            $tree[] = array(
                'category' => $category,
                'sons' => $sons ? $this->__tree($card, $sons) : null,
            );
        }

        return $tree;
    }

    /**
     * @param      $list
     * @param null $parent
     *
     * @return $this
     */
    private function __treeSort($list, $parent = null)
    {
        $order = 1;
        foreach ($list as $obj) {
            $object = $this->get('fhm_tools')->dmRepository(self::$repository)->find($obj->id);
            $object->resetParents();
            $object->resetSons();
            if (isset($obj->children)) {
                $this->__treeSort($obj->children, $object);
            }
            if ($parent) {
                $object->addParent($parent);
            }
            $object->setOrder($order);
            $this->get('fhm_tools')->dmPersist($object);
            $order++;
        }

        return $this;
    }

    /**
     * @param $card
     *
     * @return JsonResponse
     * @throws \Exception
     */
    private function __refresh($card)
    {
        $categories = $this->get('fhm_tools')->dmRepository(self::$repository)->getByCardAll($card);
        $tree = $this->__tree($card, $categories);
        $response = new JsonResponse();
        $response->setData(
            array(
                'status' => 200,
                'html' => $this->renderView(
                    "::FhmCard/Template/Editor/category.html.twig",
                    array(
                        "card" => $card,
                        "categories" => $categories,
                        "tree" => $tree,
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
                    403, $this->get('translator')->trans(
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