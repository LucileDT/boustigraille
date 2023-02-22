<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();

        $lastError = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if (!is_null($currentUser))
        {
            return $this->redirectToRoute('home');
        }

        if (!is_null($lastError))
        {
            if (is_a($lastError, 'Symfony\Component\Security\Core\Exception\BadCredentialsException'))
            {
                $this->addFlash('danger', 'Username ou mot de passe invalide.');
            }
            else
            {
                if ($lastError->getMessage() == "An exception occurred in driver: SQLSTATE[HY000] [2002] Connection refused")
                {
                    $this->addFlash('danger', 'Impossibilité de se connecter à la base de données.');
                }
                else
                {
                    $this->addFlash('danger', $lastError->getMessage());
                }
            }
        }

        return $this->render('login/login.html.twig', []);
    }

    #[Route('logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
