<?php
/**
 * Home controller
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Home controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request The request.
     * @return views
     * @Security("not is_anonymous()")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('home/home.html.twig', []);
    }
}
