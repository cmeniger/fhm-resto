<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fhm\ArticleBundle\Controller\Front;

use Fhm\ArticleBundle\Document\Article;
use Fhm\ArticleBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Controller used to manage article contents in the public part of the site.
 *
 * @Route("/article")
 *
 */
class ArticleController extends Controller
{
    /**
     * @return mixed
     */
    protected function dm()
    {
        return $this->get('doctrine_mongodb')->getManager();
    }

    /**
     * @Route("/", defaults={"page": 1}, name="article_index")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="article_index_paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function indexAction($page)
    {
        $posts = $this->dm()->getRepository(Article::class)->findBy(array('active'=>true));
        return $this->render('FhmArticleBundle:Front:index.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/article/{id}", name="article_detail")
     * @ParamConverter("post", class="Fhm\ArticleBundle\Document\Article")
     * @Method("GET")
     *
     * NOTE: The $post controller argument is automatically injected by Symfony
     * after performing a database query looking for a Article with the 'slug'
     * value given in the route.
     * See http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function detailAction(Article $post)
    {
        if ('dev' === $this->getParameter('kernel.environment')) {
            dump($post, $this->get('security.token_storage')->getToken()->getUser(), new \DateTime());
        }

        return $this->render('FhmArticleBundle:Front:detail.html.twig', ['post' => $post]);
    }

}
