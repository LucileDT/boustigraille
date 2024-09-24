<?php

namespace App\EventListener;

use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Session\Session;

class RequestListener
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $connectedUser = $this->security->getUser();
        // The goal is to display an error message if the unaccent function doesn't exists
        // But we only do it if the user is connected and admin and if the request in a main request
        if ($event->isMainRequest() && $connectedUser !== null && $this->security->isGranted('ROLE_ADMIN')) {

            $connection = $this->entityManager->getConnection();

            $sql = 'SELECT unaccent(\'Testing if unaccent function exists\')';

            // Try to use unaccent function
            try {
                $connection->executeQuery($sql);
            } catch (DriverException $driverException) {
                // If the error is related to the unaccent function
                if (str_contains($driverException->getMessage(), 'function unaccent(unknown) does not exist')) {
                    // Var init
                    /** @var Session */
                    $session = $event->getRequest()->getSession();
                    $flashBag = $session->getFlashBag();
                    $unaccentErrorMessage = 'L\'extension PostgreSql unaccent n\'existe pas pour votre SGBD. ' .
                        'Vous devez exécuter dans votre SGBD la requête "CREATE EXTENSION unaccent" avec un utilisateur ayant les droits nécessaires.';

                    // Check if we already added an error message in the flashBag
                    // In case there is multiple main Kernel requests
                    $dangerFlashMessages = $flashBag->peek('danger');
                    $alreadyHasUnaccentErrorMessage = false;
                    foreach ($dangerFlashMessages as $dangerFlashMessage) {
                        if ($dangerFlashMessage === $unaccentErrorMessage) {
                            $alreadyHasUnaccentErrorMessage = true;
                        }
                    }

                    // If there is no message yet, add it
                    if (!$alreadyHasUnaccentErrorMessage) {
                        $flashBag->add('danger', $unaccentErrorMessage);
                    }
                }
            }

            return;
        }
    }
}