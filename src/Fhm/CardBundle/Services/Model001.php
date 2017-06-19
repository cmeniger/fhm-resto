<?php

namespace Fhm\CardBundle\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Model001
 *
 * @package Fhm\CardBundle\Services
 */
class Model001 extends ModelDefault
{
    /**
     * Model001 constructor.
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
        parent::__construct($tools, $manager, $twig_engine, $form_factory, $token_storage);
        $this->initData('M001');
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
            // Ingredients
            $ingredients = explode(',', $data['ingredient']);
            $product->resetIngredients();
            foreach($ingredients as $ingredient)
            {
                $ingredient = trim($ingredient);
                if($ingredient != '')
                {
                    $object = $this->fhm_tools->dmRepository('FhmCardBundle:CardIngredient')->getByName($ingredient);
                    if($object == '')
                    {
                        $object = new \Fhm\CardBundle\Document\CardIngredient();
                        $object->setCard($product->getCard());
                        $object->setName($ingredient);
                        $object->setAlias($this->fhm_tools->getAlias($object->getId(), $object->getName(), 'FhmCardBundle:CardIngredient'));
                        $object->setUserCreate($this->user);
                        $object->setActive(true);
                        $this->fhm_tools->dmPersist($object);
                    }
                    $product->addIngredient($object);
                }
            }
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
                    "template" => strtolower($this->template)
                )
            )
        );
    }

    /**
     * @param Request $request
     * @param         $idCard
     * @param         $idCategory
     * @param         $idProduct
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
            $product->setUserUpdate($this->getUser());
            $product->setAlias($this->fhm_tools->getAlias($product->getId(), $product->getName(), 'FhmCardBundle:CardProduct'));
            $product->setCard($card);
            // Ingredients
            $ingredients = explode(',', $data['ingredient']);
            $product->resetIngredients();
            foreach($ingredients as $ingredient)
            {
                $ingredient = trim($ingredient);
                if($ingredient != '')
                {
                    $object = $this->dmRepository('FhmCardBundle:CardIngredient')->getByName($ingredient);
                    if($object == '')
                    {
                        $object = new \Fhm\CardBundle\Document\CardIngredient();
                        $object->setCard($product->getCard());
                        $object->setName($ingredient);
                        $object->setAlias($this->fhm_tools->getAlias($object->getId(), $object->getName(), 'FhmCardBundle:CardIngredient'));
                        $object->setUserCreate($this->user);
                        $object->setActive(true);
                        $this->fhm_tools->dmPersist($object);
                    }
                    $product->addIngredient($object);
                }
            }
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
                    "template" => strtolower($this->template)
                )
            )
        );
    }
}