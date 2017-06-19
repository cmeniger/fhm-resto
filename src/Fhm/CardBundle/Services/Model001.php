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
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $instance = $this->instanceData();
        $this->authorized($card);
        $product = new \Fhm\CardBundle\Document\CardProduct();
        if($category)
        {
            $product->addCategory($category);
        }
        $classType    = $this->form->product->create;
        $classHandler = $this->form->handler->create;
        $form         = $this->createForm(new $classType($instance, $product, $card), $product);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $product->setUserCreate($this->getUser());
            $product->setAlias($this->getAlias($category->getId(), $category->getName()));
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
                        $object->setAlias($this->getAlias('', $ingredient));
                        $object->setUserCreate($this->getUser());
                        $object->setActive(true);
                        $this->dmPersist($object);
                    }
                    $product->addIngredient($object);
                }
            }
            $this->dmPersist($product);

            return $this->productRefresh($card, $category, $master, $product, $instance);
        }

        return new Response(
            $this->container->get('templating')->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Product/create.html.twig",
                array(
                    "card"     => $card,
                    "category" => $category,
                    "master"   => $master,
                    "product"  => $product,
                    "form"     => $form->createView(),
                    "template" => strtolower($this->template),
                    "instance" => $instance
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
        $card     = $this->dmRepository('FhmCardBundle:Card')->find($idCard);
        $category = $this->dmRepository('FhmCardBundle:CardCategory')->find($idCategory);
        $master   = $this->dmRepository('FhmCardBundle:CardCategory')->find($idMaster);
        $product  = $this->dmRepository('FhmCardBundle:CardProduct')->find($idProduct);
        $instance = $this->instanceData();
        $this->authorized($card);
        if($product == "")
        {
            throw new NotFoundHttpException($this->trans('.error.unknown'));
        }
        $classType    = $this->form->product->update;
        $classHandler = $this->form->handler->update;
        $form         = $this->createForm(new $classType($instance, $product, $card), $product);
        $handler      = new $classHandler($form, $request);
        $process      = $handler->process();
        if($process)
        {
            $data = $request->get($form->getName());
            // Persist
            $product->setUserUpdate($this->getUser());
            $product->setAlias($this->getAlias($product->getId(), $product->getName()));
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
                        $object->setAlias($this->getAlias('', $ingredient));
                        $object->setUserCreate($this->getUser());
                        $object->setActive(true);
                        $this->dmPersist($object);
                    }
                    $product->addIngredient($object);
                }
            }
            $this->dmPersist($product);

            return $this->productRefresh($card, $category, $master, $product, $instance);
        }

        return new Response(
            $this->container->get('templating')->render(
                "::FhmCard/Template/Editor/" . $this->template . "/Product/update.html.twig",
                array(
                    "card"     => $card,
                    "category" => $category,
                    "master"   => $master,
                    "product"  => $product,
                    "form"     => $form->createView(),
                    "template" => strtolower($this->template),
                    "instance" => $instance
                )
            )
        );
    }
}