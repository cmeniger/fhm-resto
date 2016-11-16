<?php

namespace Fhm\ArticleBundle\Controller\Admin;

use Fhm\ArticleBundle\Document\Article;
use Fhm\ArticleBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/admin/article")
 * @Security("has_role('ROLE_ADMIN')")
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
     * Lists all article documents.
     *
     * This controller responds to two different routes with the same URL:
     *   * 'admin_article_index' is the route with a name that follows the same
     *     structure as the rest of the controllers of this class.
     *   * 'admin_index' is a nice shortcut to the backend homepage. This allows
     *     to create simpler links in the templates. Moreover, in the future we
     *     could move this annotation to any other controller while maintaining
     *     the route name and therefore, without breaking any existing link.
     *
     * @Route("/", name="admin_index")
     * @Route("/", name="admin_article_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $articles = $this->dm()->getRepository(Article::class)->findAll();
        return $this->render('FhmArticleBundle:Admin:index.html.twig', ['posts' => $articles]);
    }

    /**
     * Creates a new Article document.
     *
     * @Route("/create", name="admin_article_create")
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function createAction(Request $request)
    {
        $post = new  Article();
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(ArticleType::class, $post)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //appel au service
            $post->setName($this->get('slugger')->slugify($post->getTitle()));
            $this->dm()->persist($post);
            $this->dm()->flush();
            
            $this->addFlash('success', 'article.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_article_create');
            }

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('FhmArticleBundle:Admin:create.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Article document.
     *
     * @Route("/{id}", requirements={"id":"[a-z0-9]*"}, name="admin_article_detail")
     * @ParamConverter("post", class="Fhm\ArticleBundle\Document\Article")
     * @Method("GET")
     */
    public function detailAction(Article $post)
    {
//        $post = $this->dm()->getRepository(Article::class)->find($id);
        // This security check can also be performed:
        //   1. Using an annotation: @Security("post.isAuthor(user)")
        //   2. Using a "voter" (see http://symfony.com/doc/current/cookbook/security/voters_data_permission.html)
//        if (null === $this->getUser() || !$post->isAuthor($this->getUser())) {
//            throw $this->createAccessDeniedException('Posts can only be shown to their authors.');
//        }
//        $deleteForm = $this->createDeleteForm($post);

        return $this->render('FhmArticleBundle:Admin:detail.html.twig', [
            'post'        => $post,
//            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/update/{id}", requirements={"id":"[a-z0-9]*"}, name="admin_article_update")
     * @ParamConverter("post", class="Fhm\ArticleBundle\Document\Article")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Article $post, Request $request)
    {
        //$post = $this->dm()->getRepository(Article::class)->find($id);
        $editForm = $this->createForm(ArticleType::class, $post);
        $deleteForm = $this->createDeleteForm($post);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->dm()->persist($post);
            $this->dm()->flush();
            $this->addFlash('success', 'article.updated_successfully');
            return $this->redirectToRoute('admin_article_update', ['id' => $post->getId()]);
        }

        return $this->render('FhmArticleBundle:Admin:update.html.twig', [
            'post'        => $post,
            'form'        => $editForm->createView(),
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a article document.
     *
     * @Route("/delete/{id}", requirements={"post_id":"[a-z0-9]*"}, name="admin_article_delete")
     * @ParamConverter("post", class="Fhm\ArticleBundle\Document\Article")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     * The isAuthor() method is defined in the AppBundle\Entity\Post entity.
     */
    public function deleteAction(Article $post)
    {
        if (is_object($post)) {
            $this->dm()->remove($post);
            $this->dm()->flush();
            $this->addFlash('success', 'article.deleted_successfully');
        }
        return $this->redirectToRoute('admin_article_index');
    }

}
