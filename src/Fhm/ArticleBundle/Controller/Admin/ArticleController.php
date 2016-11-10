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
        $dm = $this->get('doctrine_mongodb')->getManager();
        $articles = $dm->getRepository(Article::class)->findAll();
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
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($post);
            $dm->flush();
            
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
     * @Method("GET")
     */
    public function detailAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $post = $dm->getRepository(Article::class)->find($id);

        // This security check can also be performed:
        //   1. Using an annotation: @Security("post.isAuthor(user)")
        //   2. Using a "voter" (see http://symfony.com/doc/current/cookbook/security/voters_data_permission.html)
//        if (null === $this->getUser() || !$post->isAuthor($this->getUser())) {
//            throw $this->createAccessDeniedException('Posts can only be shown to their authors.');
//        }

        $deleteForm = $this->createDeleteForm($post);

        return $this->render('FhmArticleBundle:Admin:detail.html.twig', [
            'post'        => $post,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id}/update", requirements={"id":"[a-z0-9]*"}, name="admin_article_update")
     * @Method({"GET", "POST"})
     */
    public function updateAction($id, Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $post = $dm->getRepository(Article::class)->find($id);
//        if (null === $this->getUser() || !$post->isAuthor($this->getUser())) {
//            throw $this->createAccessDeniedException('Posts can only be edited by their authors.');
//        }
        $editForm = $this->createForm(ArticleType::class, $post);
        $deleteForm = $this->createDeleteForm($post);

        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $dm->persist($post);
            $dm->flush();
            $this->addFlash('success', 'article.updated_successfully');

            return $this->redirectToRoute('admin_article_update', ['id' => $post->getId()]);
        }

        return $this->render('FhmArticleBundle:Admin:update.html.twig', [
            'post'        => $post,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}", name="admin_article_delete")
     * @Method("DELETE")
     * @Security("post.isAuthor(user)")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     * The isAuthor() method is defined in the AppBundle\Entity\Post entity.
     */
    public function deleteAction(Request $request, Article $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($post);
            $entityManager->flush();

            $this->addFlash('success', 'article.deleted_successfully');
        }

        return $this->redirectToRoute('admin_article_index');
    }

    /**
     * Creates a form to delete a Post entity by id.
     *
     * This is necessary because browsers don't support HTTP methods different
     * from GET and POST. Since the controller that removes the blog posts expects
     * a DELETE method, the trick is to create a simple form that *fakes* the
     * HTTP DELETE method.
     * See http://symfony.com/doc/current/cookbook/routing/method_parameters.html.
     *
     * @param Post $post The post object
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_article_delete', ['id' => $post->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
