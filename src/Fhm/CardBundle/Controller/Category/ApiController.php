<?php
namespace Fhm\CardBundle\Controller\Category;

use Fhm\FhmBundle\Controller\RefApiController as FhmController;
use Fhm\CardBundle\Document\CardCategory;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/api/cardcategory" , service="fhm_card_controller_category_api")
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
        parent::__construct('Fhm', 'Card', 'card_category', 'CardCategory');
        $this->form->type->create = 'Fhm\\CardBundle\\Form\\Type\\Api\\Category\\CreateType';
        $this->form->type->update = 'Fhm\\CardBundle\\Form\\Type\\Api\\Category\\UpdateType';
        $this->translation        = array('FhmCardBundle', 'card.category');
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
     *      path="/historic",
     *      name="fhm_api_card_category_historic"
     * )
     * @Template("::FhmCard/Api/Category/historic.html.twig")
     */
    public function historicAction(Request $request)
    {
        return parent::historicAction($request);
    }

    /**
     * @Route
     * (
     *      path="/historic/copy/{id}",
     *      name="fhm_api_card_category_historic_copy",
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
     *      name="fhm_api_card_category_embed",
     *      requirements={"idCard"="[a-z0-9]*", "idCategory"="[a-z0-9]*"},
     *      defaults={"template"="inline"}
     * )
     */
    public function embedAction(Request $request, $idCard, $idCategory, $template)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idCategory);
        $instance = $this->fhm_tools->instanceData($document);
        // ERROR - unknown
        if ($card == "")
        {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('card.error.unknown', array(), $this->translation[0])
            );
        }
        if ($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Category/" . $template . ".html.twig",
                array(
                    "card"      => $card,
                    "document"  => $document,
                    "documents" => $this->fhm_tools->dmRepository()->getSons($card, $document, $instance->grouping->filtered),
                    "instance"  => $instance,
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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($id);
        $instance = $this->fhm_tools->instanceData($card);
        $this->_authorized($card);
        $categories = $this->fhm_tools->dmRepository()->getByCardAll($card, $instance->grouping->filtered);
        $tree       = $this->_tree($card, $categories, $instance);

        return new Response(
            $this->renderView(
                "::FhmCard/Template/Editor/category.html.twig",
                array(
                    "card"       => $card,
                    "categories" => $categories,
                    "tree"       => $tree,
                    "instance"   => $instance,
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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($id);
        $instance = $this->fhm_tools->instanceData($card);
        $this->_authorized($card);
        $list = json_decode($request->get('list'));
        $this->_treeSort($list);
        $response = new JsonResponse();
        $response->setData(array(
            'status' => 200
        ));

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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository()->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        $document = $this->document;
        if($category)
        {
            $document->addParent($category);
        }
        $classType    = $this->form->type->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $document, $card), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserCreate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $document->setCard($card);
            $this->fhm_tools->dmPersist($document);

            return $this->_refresh($card, $instance);
        }

        return array(
            'card'     => $card,
            'category' => $category,
            'form'     => $form->createView(),
            'instance' => $instance
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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        $classType    = $this->form->type->update;
        $classHandler = $this->form->handler->update;
        $form         = $this->createForm(new $classType($instance, $document, $card), $document);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $document->setUserUpdate($this->getUser());
            $document->setAlias($this->getAlias($document->getId(), $document->getName()));
            $this->fhm_tools->dmPersist($document);

            return $this->_refresh($card, $instance);
        }

        return array(
            'card'     => $card,
            'document' => $document,
            'form'     => $form->createView(),
            'instance' => $instance
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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(true);
        $this->fhm_tools->dmPersist($document);

        return $this->_refresh($card, $instance);
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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        $document->setUserUpdate($this->getUser());
        $document->setActive(false);
        $this->fhm_tools->dmPersist($document);

        return $this->_refresh($card, $instance);
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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Delete
        if($document->getDelete())
        {
            $this->fhm_tools->dmRemove($document);
        }
        else
        {
            $document->setUserUpdate($this->getUser());
            $document->setDelete(true);
            $this->fhm_tools->dmPersist($document);
        }

        return $this->_refresh($card, $instance);
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
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $document = $this->fhm_tools->dmRepository()->find($idCategory);
        $instance = $this->fhm_tools->instanceData();
        $this->_authorized($card);
        if($document == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans($this->translation[1] . '.error.unknown', array(), $this->translation[0]));
        }
        // Undelete
        $document->setUserUpdate($this->getUser());
        $document->setDelete(false);
        $this->fhm_tools->dmPersist($document);

        return $this->_refresh($card, $instance);
    }

    /**
     * @param $card
     * @param $categories
     * @param $instance
     *
     * @return array
     */
    private function _tree($card, $categories, $instance)
    {
        $tree = array();
        foreach($categories as $category)
        {
            $sons   = $this->fhm_tools->dmRepository()->getSonsAll($card, $category, $instance->grouping->filtered);
            $tree[] = array(
                'category' => $category,
                'sons'     => $sons ? $this->_tree($card, $sons, $instance) : null
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
    private function _treeSort($list, $parent = null)
    {
        $order = 1;
        foreach($list as $obj)
        {
            $document = $this->fhm_tools->dmRepository()->find($obj->id);
            $document->resetParents();
            $document->resetSons();
            if(isset($obj->children))
            {
                $this->_treeSort($obj->children, $document);
            }
            if($parent)
            {
                $document->addParent($parent);
            }
            $document->setOrder($order);
            $this->fhm_tools->dmPersist($document);
            $order++;
        }

        return $this;
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
        $categories = $this->fhm_tools->dmRepository()->getByCardAll($card, $instance->grouping->filtered);
        $tree       = $this->_tree($card, $categories, $instance);
        $response   = new JsonResponse();
        $response->setData(array(
            'status' => 200,
            'html'   => $this->renderView(
                "::FhmCard/Template/Editor/category.html.twig",
                array(
                    "card"       => $card,
                    "categories" => $categories,
                    "tree"       => $tree,
                    "instance"   => $instance,
                ))
        ));

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
        if($card == "")
        {
            throw $this->createNotFoundException($this->get('translator')->trans('card.error.unknown', array(), $this->translation[0]));
        }
        if($card->getParent() && method_exists($card->getParent(), 'hasModerator'))
        {
            if(!$this->getUser()->isSuperAdmin() && !$this->getUser()->hasRole('ROLE_ADMIN') && !$card->getParent()->hasModerator($this->getUser()))
            {
                throw new HttpException(403, $this->get('translator')->trans($this->translation[1] . '.error.forbidden', array(), $this->translation[0]));
            }
        }

        return $this;
    }
}