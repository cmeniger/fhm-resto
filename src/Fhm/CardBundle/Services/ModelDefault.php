<?php

namespace Fhm\CardBundle\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ModelDefault
 *
 * @package Fhm\CardBundle\Services
 */
class ModelDefault
{
    protected $fhm_tools;
    protected $fhm_manager;
    protected $twig_engine;
    protected $form_factory;
    protected $template;
    protected $form;
    protected $user;

    /**
     * ModelDefault constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools                                              $tools
     * @param \Fhm\FhmBundle\Manager\FhmObjectManager                                    $manager
     * @param \Symfony\Bundle\TwigBundle\TwigEngine                                      $twig_engine
     * @param \Symfony\Component\Form\FormFactory                                        $form_factory
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $token_storage
     */
    public function __construct(
        \Fhm\FhmBundle\Services\Tools $tools,
        \Fhm\FhmBundle\Manager\FhmObjectManager $manager,
        \Symfony\Bundle\TwigBundle\TwigEngine $twig_engine,
        \Symfony\Component\Form\FormFactory $form_factory,
        \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $token_storage
    ) {
        $this->fhm_tools    = $tools;
        $this->fhm_manager  = $manager;
        $this->twig_engine  = $twig_engine;
        $this->form_factory = $form_factory;
        $this->user         = $token_storage->getToken()->getUser();
        $this->initData('Default');
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function initData($template = 'Default')
    {
        $this->template = $template;
        $this->form     = new \stdClass();
        // Form category
        $this->form->category         = new \stdClass();
        $this->form->category->create = '\\Fhm\\CardBundle\\Form\\Type\\Model\\' . $template . 'CategoryCreateType';
        $this->form->category->update = '\\Fhm\\CardBundle\\Form\\Type\\Model\\' . $template . 'CategoryUpdateType';
        // Form product
        $this->form->product         = new \stdClass();
        $this->form->product->create = '\\Fhm\\CardBundle\\Form\\Type\\Model\\' . $template . 'ProductCreateType';
        $this->form->product->update = '\\Fhm\\CardBundle\\Form\\Type\\Model\\' . $template . 'ProductUpdateType';
        // Form ingredient
        $this->form->ingredient         = new \stdClass();
        $this->form->ingredient->create = '\\Fhm\\CardBundle\\Form\\Type\\Model\\' . $template . 'IngredientCreateType';
        $this->form->ingredient->update = '\\Fhm\\CardBundle\\Form\\Type\\Model\\' . $template . 'IngredientUpdateType';
        // Handler
        $this->form->handler         = new \stdClass();
        $this->form->handler->create = '\\Fhm\\FhmBundle\\Form\\Handler\\Api\\CreateHandler';
        $this->form->handler->update = '\\Fhm\\FhmBundle\\Form\\Handler\\Api\\UpdateHandler';

        return $this;
    }

    /**
     * @param $idCard
     *
     * @return Response
     */
    public function index($idCard)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/index.html.twig",
                array(
                    "document" => $card,
                    "template" => strtolower($this->template)
                )
            )
        );
    }

    /**
     * @param $idCard
     * @param $idCategory
     *
     * @return Response
     */
    public function categoryIndex($idCard, $idCategory)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $categories = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN'));
        $tree       = $this->categoryTree($card, $categories);

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Category/index.html.twig",
                array(
                    "card"       => $card,
                    "categories" => $categories,
                    "products"   => $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN')),
                    "tree"       => $tree,
                    "template"   => strtolower($this->template)
                )
            )
        );
    }

    /**
     * @param $card
     * @param $category
     * @param $master
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function categoryRefresh($card, $category, $master)
    {
        $categories = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN'));
        $tree       = $this->categoryTree($card, $categories);
        $response   = new JsonResponse();
        $response->setData(array(
            'status' => 200,
            'html'   => $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Category/index.html.twig",
                array(
                    "card"       => $card,
                    "categories" => $categories,
                    "products"   => $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->setSort('alias')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN')),
                    "tree"       => $tree,
                    "template"   => strtolower($this->template),
                ))
        ));

        return $response;
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idCategory
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function categorySort(Request $request, $idCard, $idCategory)
    {
        $card   = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $parent = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $this->authorized($card);
        $this->categoryTreeSort(json_decode($request->get('list')), $parent);
        $response = new JsonResponse();
        $response->setData(array(
            'status' => 200
        ));

        return $response;
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idCategory
     * @param         $idMaster
     *
     * @return JsonResponse|Response
     */
    public function categoryCreate(Request $request, $idCard, $idCategory, $idMaster)
    {
        $card   = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $parent = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $this->authorized($card);
        $class    = $this->fhm_manager->getCurrentModelName('FhmCardBundle:CardCategory');
        $category = new $class;
        if($parent)
        {
            $category->addParent($parent);
        }
        $form    = $this->form_factory->create(
            $this->form->category->create,
            $category,
            array(
                'user_admin'     => $this->user->hasRole('ROLE_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->fhm_manager,
                'card'           => $idCard
            )
        );
        $handler = new $this->form->handler->create($form, $request);
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $category->setUserCreate($this->user);
            $category->setAlias($this->fhm_tools->getAlias($category->getId(), $category->getName(), 'FhmCardBundle:CardCategory'));
            $category->setCard($card);
            $this->fhm_tools->dmPersist($category);

            return $this->categoryRefresh($card, $category, $master);
        }

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Category/create.html.twig",
                array(
                    "card"     => $card,
                    "category" => $category,
                    "parent"   => $parent,
                    "master"   => $master,
                    "form"     => $form->createView(),
                    "template" => strtolower($this->template)
                )
            )
        );
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idCategory
     * @param         $idMaster
     *
     * @return JsonResponse|Response
     */
    public function categoryUpdate(Request $request, $idCard, $idCategory, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $this->authorized($card);
        if($category == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.category.error.unknown', array(), 'FhmCardBundle'));
        }
        $class   = $this->fhm_manager->getCurrentModelName('FhmCardBundle:CardCategory');
        $form    = $this->form_factory->create(
            $this->form->category->create,
            $category,
            array(
                'user_admin'     => $this->user->hasRole('ROLE_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->fhm_manager,
                'card'           => $idCard
            )
        );
        $handler = new $this->form->handler->create($form, $request);
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $category->setUserUpdate($this->user);
            $category->setAlias($this->fhm_tools->getAlias($category->getId(), $category->getName(), 'FhmCardBundle:CardCategory'));
            $this->fhm_tools->dmPersist($category);

            return $this->categoryRefresh($card, $category, $master);
        }

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Category/update.html.twig",
                array(
                    "card"     => $card,
                    "category" => $category,
                    "master"   => $master,
                    "form"     => $form->createView(),
                    "template" => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function categoryActivate($idCard, $idCategory, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $this->authorized($card);
        if($category == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.category.error.unknown', array(), 'FhmCardBundle'));
        }
        $category->setUserUpdate($this->user);
        $category->setActive(true);
        $this->fhm_tools->dmPersist($category);

        return $this->categoryRefresh($card, $category, $master);
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function categoryDeactivate($idCard, $idCategory, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $this->authorized($card);
        if($category == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.category.error.unknown', array(), 'FhmCardBundle'));
        }
        $category->setUserUpdate($this->user);
        $category->setActive(false);
        $this->fhm_tools->dmPersist($category);

        return $this->categoryRefresh($card, $category, $master);
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function categoryDelete($idCard, $idCategory, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $this->authorized($card);
        if($category == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.category.error.unknown', array(), 'FhmCardBundle'));
        }
        // Delete
        if($category->getDelete())
        {
            $this->fhm_tools->dmRemove($category);
        }
        else
        {
            $category->setUserUpdate($this->user);
            $category->setDelete(true);
            $this->fhm_tools->dmPersist($category);
        }

        return $this->categoryRefresh($card, $category, $master);
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function categoryUndelete($idCard, $idCategory, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $this->authorized($card);
        if($category == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.category.error.unknown', array(), 'FhmCardBundle'));
        }
        // Undelete
        $category->setUserUpdate($this->user);
        $category->setDelete(false);
        $this->fhm_tools->dmPersist($category);

        return $this->categoryRefresh($card, $category, $master);
    }

    /**
     * @param $card
     * @param $categories
     *
     * @return array
     */
    public function categoryTree($card, $categories)
    {
        $tree = array();
        foreach($categories as $category)
        {
            $sons   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getSonsAll($card, $category);
            $tree[] = array(
                'category' => $category,
                'sons'     => $sons ? $this->categoryTree($card, $sons) : null
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
    public function categoryTreeSort($list, $parent = null)
    {
        $order = 1;
        foreach($list as $obj)
        {
            $document = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($obj->id);
            $document->resetParents();
            $document->resetSons();
            if(isset($obj->children))
            {
                $this->categoryTreeSort($obj->children, $document);
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
     * @param $idCard
     * @param $idCategory
     *
     * @return Response
     */
    public function productIndex($idCard, $idCategory)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $categories = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN'));
        $tree       = $this->productTree($card, $categories);

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Product/index.html.twig",
                array(
                    "card"       => $card,
                    "categories" => $categories,
                    "products"   => $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN')),
                    "tree"       => $tree,
                    "template"   => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param $card
     * @param $category
     * @param $master
     * @param $product
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function productRefresh($card, $category, $master, $product)
    {
        $categories = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN'));
        $tree       = $this->productTree($card, $categories);
        $response   = new JsonResponse();
        $response->setData(array(
            'status' => 200,
            'html'   => $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Product/index.html.twig",
                array(
                    "card"       => $card,
                    "categories" => $categories,
                    "products"   => $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->setSort('alias')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN')),
                    "tree"       => $tree,
                    "template"   => strtolower($this->template),
                ))
        ));

        return $response;
    }

    /**
     * @param Request $request
     * @param         $idCard
     *
     * @return Response
     */
    public function productSearch(Request $request, $idCard)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Product/search.html.twig",
                array(
                    "card"     => $card,
                    "products" => $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->setSort('alias')->getByCardSearch($card, $request->get('search')),
                    "template" => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idMaster
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function productSort(Request $request, $idCard, $idMaster)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $this->productTreeSort(json_decode($request->get('list')));
        $response = new JsonResponse();
        $response->setData(array(
            'status' => 200
        ));

        return $response;
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idCategory
     * @param         $idMaster
     *
     * @return JsonResponse|Response
     */
    public function productCreate(Request $request, $idCard, $idCategory, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $this->authorized($card);
        $class   = $this->fhm_manager->getCurrentModelName('FhmCardBundle:CardProduct');
        $product = new $class;
        if($category)
        {
            $product->addCategory($category);
        }
        $form    = $this->form_factory->create(
            $this->form->product->create,
            $product,
            array(
                'user_admin'     => $this->user->hasRole('ROLE_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->fhm_manager,
                'card'           => $idCard
            )
        );
        $handler = new $this->form->handler->create($form, $request);
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $product->setUserCreate($this->user);
            $product->setAlias($this->fhm_tools->getAlias($product->getId(), $product->getName(), 'FhmCardBundle:CardProduct'));
            $product->setCard($card);
            $this->fhm_tools->dmPersist($product);

            return $this->productRefresh($card, $category, $master, $product);
        }

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Product/create.html.twig",
                array(
                    "card"     => $card,
                    "category" => $category,
                    "master"   => $master,
                    "product"  => $product,
                    "form"     => $form->createView(),
                    "template" => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idCategory
     * @param         $idProduct
     * @param         $idMaster
     *
     * @return JsonResponse|Response
     */
    public function productUpdate(Request $request, $idCard, $idCategory, $idProduct, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $product  = $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        $this->authorized($card);
        if($product == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.product.error.unknown', array(), 'FhmCardBundle'));
        }
        $class   = $this->fhm_manager->getCurrentModelName('FhmCardBundle:CardProduct');
        $form    = $this->form_factory->create(
            $this->form->product->create,
            $product,
            array(
                'user_admin'     => $this->user->hasRole('ROLE_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->fhm_manager,
                'card'           => $idCard
            )
        );
        $handler = new $this->form->handler->create($form, $request);
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $product->setUserUpdate($this->user);
            $product->setAlias($this->fhm_tools->getAlias($product->getId(), $product->getName(), 'FhmCardBundle:CardProduct'));
            $product->setCard($card);
            $this->fhm_tools->dmPersist($product);

            return $this->productRefresh($card, $category, $master, $product);
        }

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Product/update.html.twig",
                array(
                    "card"     => $card,
                    "category" => $category,
                    "master"   => $master,
                    "product"  => $product,
                    "form"     => $form->createView(),
                    "template" => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idProduct
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function productActivate($idCard, $idCategory, $idProduct, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $product  = $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        $this->authorized($card);
        if($product == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.product.error.unknown', array(), 'FhmCardBundle'));
        }
        $product->setUserUpdate($this->user);
        $product->setActive(true);
        $this->fhm_tools->dmPersist($product);

        return $this->productRefresh($card, $category, $master, $product);
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idProduct
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function productDeactivate($idCard, $idCategory, $idProduct, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $product  = $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        $this->authorized($card);
        if($product == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.product.error.unknown', array(), 'FhmCardBundle'));
        }
        $product->setUserUpdate($this->user);
        $product->setActive(false);
        $this->fhm_tools->dmPersist($product);

        return $this->productRefresh($card, $category, $master, $product);
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idProduct
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function productDelete($idCard, $idCategory, $idProduct, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $product  = $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        $this->authorized($card);
        if($product == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.product.error.unknown', array(), 'FhmCardBundle'));
        }
        // Delete
        if($product->getDelete())
        {
            $this->fhm_tools->dmRemove($product);
        }
        else
        {
            $product->setUserUpdate($this->user);
            $product->setDelete(true);
            $this->fhm_tools->dmPersist($product);
        }

        return $this->productRefresh($card, $category, $master, $product);
    }

    /**
     * @param $idCard
     * @param $idCategory
     * @param $idProduct
     * @param $idMaster
     *
     * @return JsonResponse
     */
    public function productUndelete($idCard, $idCategory, $idProduct, $idMaster)
    {
        $card     = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $product  = $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        $this->authorized($card);
        if($product == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.product.error.unknown', array(), 'FhmCardBundle'));
        }
        // Undelete
        $product->setUserUpdate($this->user);
        $product->setDelete(false);
        $this->fhm_tools->dmPersist($product);

        return $this->productRefresh($card, $category, $master, $product);
    }

    /**
     * @param      $card
     * @param      $categories
     * @param null $use
     *
     * @return array
     */
    public function productTree($card, $categories, &$use = null)
    {
        $use  = $use ? $use : new ArrayCollection();
        $tree = array();
        foreach($categories as $category)
        {
            if(!$use->contains($category))
            {
                $use->add($category);
                $sons   = $this->fhm_tools->dmRepository('FhmCardBundle:CardCategory')->getSonsAll($card, $category);
                $tree[] = array(
                    'category' => $category,
                    'products' => $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->getByCategoryAll($card, $category),
                );
                if($sons)
                {
                    $tree = array_merge($tree, $this->productTree($card, $sons, $use));
                }
            }
        }

        return $tree;
    }

    /**
     * @param $list
     *
     * @return $this
     */
    public function productTreeSort($list)
    {
        $order = 1;
        foreach($list as $obj)
        {
            $document = $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->find($obj->id);
            $document->setOrder($order);
            $this->fhm_tools->dmPersist($document);
            $order++;
        }

        return $this;
    }

    /**
     * @param $idCard
     *
     * @return Response
     */
    public function ingredientIndex($idCard)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $tree = $this->ingredientTree($card);

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Ingredient/index.html.twig",
                array(
                    "card"     => $card,
                    "products" => $this->fhm_tools->dmRepository('FhmCardBundle:CardProduct')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN')),
                    "tree"     => $tree,
                    "template" => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param $card
     * @param $ingredient
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function ingredientRefresh($card, $ingredient)
    {
        $ingredients = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN'));
        $tree        = $this->ingredientTree($card);
        $response    = new JsonResponse();
        $response->setData(array(
            'status' => 200,
            'html'   => $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Ingredient/index.html.twig",
                array(
                    "card"        => $card,
                    "ingredients" => $ingredients,
                    "tree"        => $tree,
                    "template"    => strtolower($this->template),
                ))
        ));

        return $response;
    }

    /**
     * @param Request $request
     * @param         $idCard
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function ingredientSort(Request $request, $idCard)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $this->ingredientTreeSort(json_decode($request->get('list')));
        $response = new JsonResponse();
        $response->setData(array(
            'status' => 200
        ));

        return $response;
    }

    /**
     * @param Request $request
     * @param         $idCard
     *
     * @return JsonResponse|Response
     */
    public function ingredientCreate(Request $request, $idCard)
    {
        $card = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($card);
        $class      = $this->fhm_manager->getCurrentModelName('FhmCardBundle:CardIngredient');
        $ingredient = new $class;
        $form       = $this->form_factory->create(
            $this->form->ingredient->create,
            $ingredient,
            array(
                'user_admin'     => $this->user->hasRole('ROLE_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->fhm_manager,
                'card'           => $idCard
            )
        );
        $handler    = new $this->form->handler->create($form, $request);
        $process    = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $ingredient->setUserCreate($this->user);
            $ingredient->setAlias($this->fhm_tools->getAlias($ingredient->getId(), $ingredient->getName(), 'FhmCardBundle:CardIngredient'));
            $ingredient->setCard($card);
            $this->fhm_tools->dmPersist($ingredient);

            return $this->ingredientRefresh($card, $ingredient);
        }

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Ingredient/create.html.twig",
                array(
                    "card"     => $card,
                    "form"     => $form->createView(),
                    "template" => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idIngredient
     *
     * @return JsonResponse|Response
     */
    public function ingredientUpdate(Request $request, $idCard, $idIngredient)
    {
        $card       = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $ingredient = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->find($idIngredient);
        $this->authorized($card);
        if($ingredient == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.ingredient.error.unknown', array(), 'FhmCardBundle'));
        }
        $class   = $this->fhm_manager->getCurrentModelName('FhmCardBundle:CardIngredient');
        $form    = $this->form_factory->create(
            $this->form->ingredient->update,
            $ingredient,
            array(
                'user_admin'     => $this->user->hasRole('ROLE_ADMIN'),
                'data_class'     => $class,
                'object_manager' => $this->fhm_manager,
                'card'           => $idCard
            )
        );
        $handler = new $this->form->handler->create($form, $request);
        $process = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $ingredient->setUserUpdate($this->user);
            $ingredient->setAlias($this->fhm_tools->getAlias($ingredient->getId(), $ingredient->getName(), 'FhmCardBundle:CardIngredient'));
            $ingredient->setCard($card);
            $this->fhm_tools->dmPersist($ingredient);

            return $this->ingredientRefresh($card, $ingredient);
        }

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Ingredient/update.html.twig",
                array(
                    "card"       => $card,
                    "ingredient" => $ingredient,
                    "form"       => $form->createView(),
                    "template"   => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param $idCard
     * @param $idIngredient
     *
     * @return JsonResponse
     */
    public function ingredientActivate($idCard, $idIngredient)
    {
        $card       = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $ingredient = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->find($idIngredient);
        $this->authorized($card);
        if($ingredient == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.ingredient.error.unknown', array(), 'FhmCardBundle'));
        }
        $ingredient->setUserUpdate($this->user);
        $ingredient->setActive(true);
        $this->fhm_tools->dmPersist($ingredient);

        return $this->ingredientRefresh($card, $ingredient);
    }

    /**
     * @param $idCard
     * @param $idIngredient
     *
     * @return JsonResponse
     */
    public function ingredientDeactivate($idCard, $idIngredient)
    {
        $card       = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $ingredient = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->find($idIngredient);
        $this->authorized($card);
        if($ingredient == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.ingredient.error.unknown', array(), 'FhmCardBundle'));
        }
        $ingredient->setUserUpdate($this->user);
        $ingredient->setActive(false);
        $this->fhm_tools->dmPersist($ingredient);

        return $this->ingredientRefresh($card, $ingredient);
    }

    /**
     * @param $idCard
     * @param $idIngredient
     *
     * @return JsonResponse
     */
    public function ingredientDelete($idCard, $idIngredient)
    {
        $card       = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $ingredient = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->find($idIngredient);
        $this->authorized($card);
        if($ingredient == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.ingredient.error.unknown', array(), 'FhmCardBundle'));
        }
        // Delete
        if($ingredient->getDelete())
        {
            $this->fhm_tools->dmRemove($ingredient);
        }
        else
        {
            $ingredient->setUserUpdate($this->user);
            $ingredient->setDelete(true);
            $this->fhm_tools->dmPersist($ingredient);
        }

        return $this->ingredientRefresh($card, $ingredient);
    }

    /**
     * @param $idCard
     * @param $idIngredient
     *
     * @return JsonResponse
     */
    public function ingredientUndelete($idCard, $idIngredient)
    {
        $card       = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $ingredient = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->find($idIngredient);
        $this->authorized($card);
        if($ingredient == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.ingredient.error.unknown', array(), 'FhmCardBundle'));
        }
        // Undelete
        $ingredient->setUserUpdate($this->user);
        $ingredient->setDelete(false);
        $this->fhm_tools->dmPersist($ingredient);

        return $this->ingredientRefresh($card, $ingredient);
    }

    /**
     * @param $card
     *
     * @return array
     */
    public function ingredientTree($card)
    {
        return $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->getByCardAll($card, $this->user->hasRole('ROLE_SUPER_ADMIN'));
    }

    /**
     * @param $list
     *
     * @return $this
     */
    public function ingredientTreeSort($list)
    {
        $order = 1;
        foreach($list as $obj)
        {
            $document = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->find($obj->id);
            $document->setOrder($order);
            $this->fhm_tools->dmPersist($document);
            $order++;
        }

        return $this;
    }

    /**
     * @param $idCard
     *
     * @return Response
     */
    public function previewIndex($idCard)
    {
        $document = $this->fhm_tools->dmRepository('FhmCardBundle:Card')->find($idCard);
        $this->authorized($document);

        return new Response(
            $this->twig_engine->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Preview/index.html.twig",
                array(
                    "document" => $document,
                    "template" => strtolower($this->template),
                )
            )
        );
    }

    /**
     * @param $card
     *
     * @return $this
     * @throws HttpException
     */
    protected function authorized($card)
    {
        if($card == "")
        {
            throw new NotFoundHttpException($this->fhm_tools->trans('card.error.unknown', array(), 'FhmCardBundle'));
        }
        if($card->getParent() && method_exists($card->getParent(), 'hasModerator'))
        {
            if(!$this->user->isSuperAdmin() && !$this->user->hasRole('ROLE_ADMIN') && !$card->getParent()->hasModerator($this->user))
            {
                throw new HttpException(403, $this->fhm_tools->trans('card.error.forbidden', array(), 'FhmCardBundle'));
            }
        }

        return $this;
    }
}