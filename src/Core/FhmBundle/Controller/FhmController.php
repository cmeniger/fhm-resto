<?php
namespace Core\FhmBundle\Controller;

/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 15/11/16
 * Time: 10:16
 */
use Core\FhmBundle\Document\Fhm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * The default front Controller .
 * Description TODO
 *
 * @author Fhm Team
 */

class FhmController extends Controller
{

    /**
     * @Route("/", defaults={"page": 1}, name="home")
     * @Method("GET")
     */
    public function indexAction($page)
    {
        return $this->render('core/index.html.twig');
    }
}