<?php

namespace App\Controller;

use App\Entity\FollowProposition;
use App\Entity\FollowType;
use App\Entity\NotificationHistory;
use App\Form\NewPasswordType;
use App\Form\PrivacySettingsType;
use App\Form\ProposeMealListFollowType;
use App\Form\ProposeUsernameInRecipeFollowType;
use App\Form\UserNutritionalDataType;
use App\FormDataObject\UserNutritionalDataFDO;
use App\Repository\FollowPropositionRepository;
use App\Repository\FollowTypeRepository;
use App\Repository\NotificationHistoryRepository;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/my-account', name: 'my_account_')]
class MyAccountController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function index(
        FollowPropositionRepository $followPropositionRepository
    ): Response
    {
        $user = $this->getUser();
        $acceptedMealListFollow = $followPropositionRepository->findFollowedOnesAccepted($user, FollowType::MEAL_LIST);
        $acceptedUsernameOnRecipeFollow = $followPropositionRepository->findFollowedOnesAccepted($user, FollowType::USERNAME_ON_RECIPE);
        return $this->render('my_account/index.html.twig', [
            'user' => $user,
            'acceptedMealListFollows' => $acceptedMealListFollow,
            'acceptedUsernameOnRecipeFollows' => $acceptedUsernameOnRecipeFollow,
        ]);
    }

    #[Route(path: '/edit-nutritional-data', name: 'edit_nutritional_data', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function editNutritionalData(
        Request $request,
        UserNutritionalDataFDO $userNutritionalDataFDO,
        EntityManagerInterface $entityManager
    ): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();
        $userNutritionalDataFDO->setProteins($currentUser->getProteins());
        $userNutritionalDataFDO->setCarbohydrates($currentUser->getCarbohydrates());
        $userNutritionalDataFDO->setFat($currentUser->getFat());
        $userNutritionalDataFDO->setEnergy($currentUser->getEnergy());

        $form = $this->createForm(UserNutritionalDataType::class, $userNutritionalDataFDO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser->setProteins($userNutritionalDataFDO->getProteins());
            $currentUser->setCarbohydrates($userNutritionalDataFDO->getCarbohydrates());
            $currentUser->setFat($userNutritionalDataFDO->getFat());
            $currentUser->setEnergy($userNutritionalDataFDO->getEnergy());

            $entityManager->persist($currentUser);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $currentUser->getId()]);
        }

        return $this->render('my_account/edit-nutritional-data.html.twig', [
            'user' => $currentUser,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/edit-password', name: 'edit_password', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function editPassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            if ($passwordHasher->isPasswordValid($user, $formData['old_password'], null)) {
                $hashedNewPassword = $passwordHasher->hashPassword($user, $formData['new_password']);
                $user->setPassword($hashedNewPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('my_account_index');
        }

        return $this->render('my_account/edit-password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/edit-privacy-settings', name: 'edit_privacy_settings', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function editPrivacySettings(
        Request $request,
        UserRepository $userRepository,
        FollowTypeRepository $followTypeRepository,
        FollowPropositionRepository $followPropositionRepository,
        EntityManagerInterface $entityManager,
        NotificationService $notificationService
    ): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $privacyForm = $this->createForm(PrivacySettingsType::class, $user);
        $privacyForm->handleRequest($request);

        $sentFollowMealListPropositions = $followPropositionRepository->findByFollowedAndType(
            $user,
            FollowType::MEAL_LIST,
        );
        $sentUsernameOnRecipeFollowPropositions = $followPropositionRepository->findByFollowedAndType(
            $user,
            FollowType::USERNAME_ON_RECIPE,
        );

        if ($privacyForm->isSubmitted() && $privacyForm->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('my_account_index');
        }

        $followPropositionUrl = $this->generateUrl('my_account_follow_propositions');

        $proposeMealListFollowForm = $this->createForm(ProposeMealListFollowType::class);
        $proposeMealListFollowForm->handleRequest($request);
        if ($proposeMealListFollowForm->isSubmitted() && $proposeMealListFollowForm->isValid()) {
            $formData = $proposeMealListFollowForm->getData();
            $username = $formData['username'];

            $askedUser = $userRepository->findOneBy(['username' => $username]);

            // if the user exists, create a follow proposition and send them a notification
            if (!empty($askedUser)) {
                $followProposition = $followPropositionRepository->findFollowProposition(
                    $askedUser,
                    $user,
                    FollowType::MEAL_LIST
                );

                if (empty($followProposition)) {
                    $followTypeMealList = $followTypeRepository->findOneBy(['code' => FollowType::MEAL_LIST]);
                    $followProposition = new FollowProposition();
                    $followProposition->setType($followTypeMealList);
                    $followProposition->setFollowed($user);
                    $followProposition->setFollower($askedUser);
                    $followProposition->setProposedAt(new \DateTimeImmutable());

                    $notification = new Notification(
                        $followTypeMealList->getLabel(),
                        ['browser']
                    );

                    $notification->content(
                        '**' . $user->getUsername() . '**' .
                        ' vous propose l\'accès à ses **listes de repas**.<br>' .
                        'Vous pouvez voir sa proposition sur la page des [Demandes de suivi](' . $followPropositionUrl . ') !'
                    );

                    $notificationService->sendNotification(
                        $notification,
                        $askedUser,
                        $user
                    );

                    $entityManager->persist($followProposition);
                    $entityManager->flush();
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('my_account_index');
        }

        $proposeUsernameInRecipeFollowForm = $this->createForm(ProposeUsernameInRecipeFollowType::class);
        $proposeUsernameInRecipeFollowForm->handleRequest($request);
        if ($proposeUsernameInRecipeFollowForm->isSubmitted() && $proposeUsernameInRecipeFollowForm->isValid()) {
            $formData = $proposeUsernameInRecipeFollowForm->getData();
            $username = $formData['username'];

            $askedUser = $userRepository->findOneBy(['username' => $username]);

            // if the user exists, create a follow proposition and send them a notification
            if (!empty($askedUser)) {
                $followProposition = $followPropositionRepository->findFollowProposition(
                    $askedUser,
                    $user,
                    FollowType::USERNAME_ON_RECIPE
                );

                if (empty($followProposition)) {
                    $followTypeUsernameOnRecipe = $followTypeRepository->findOneBy(['code' => FollowType::USERNAME_ON_RECIPE]);
                    $followProposition = new FollowProposition();
                    $followProposition->setType($followTypeUsernameOnRecipe);
                    $followProposition->setFollowed($user);
                    $followProposition->setFollower($askedUser);
                    $followProposition->setProposedAt(new \DateTimeImmutable());

                    $notification = new Notification(
                        $followTypeUsernameOnRecipe->getLabel(),
                        ['browser']
                    );

                    $notification->content(
                        '**' . $user->getUsername() . '**' .
                        ' vous propose de voir son **pseudo** dans les **recettes** qu\'ael a rédigées.<br>' .
                        'Vous pouvez voir sa proposition sur la page des [Demandes de suivi](' . $followPropositionUrl . ') !'
                    );

                    $notificationService->sendNotification(
                        $notification,
                        $askedUser,
                        $user
                    );

                    $entityManager->persist($followProposition);
                    $entityManager->flush();
                }
            }

            return $this->redirectToRoute('my_account_index');
        }

        return $this->render('my_account/privacy.html.twig', [
            'user' => $user,
            'global_settings_form' => $privacyForm->createView(),
            'ask_meal_list_follow_form' => $proposeMealListFollowForm->createView(),
            'propose_username_in_recipe_follow_form' => $proposeUsernameInRecipeFollowForm->createView(),
            'sentMealListFollowPropositions' => $sentFollowMealListPropositions,
            'sentUsernameOnRecipeFollowPropositions' => $sentUsernameOnRecipeFollowPropositions,
        ]);
    }

    #[Route(path: '/notifications', name: 'notifications', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function notifications(
        NotificationHistoryRepository $notificationHistoryRepository,
    ): Response
    {
        $user = $this->getUser();
        $unreadNotificationHistory = $notificationHistoryRepository->findUnreadByUser($user);
        $readNotificationHistory = $notificationHistoryRepository->findReadByUser($user);

        return $this->render('my_account/notifications.html.twig', [
            'user' => $user,
            'unread_notification_history' => $unreadNotificationHistory,
            'read_notification_history' => $readNotificationHistory,
        ]);
    }

    #[Route(path: '/follow-propositions', name: 'follow_propositions', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function followPropositions(
        FollowPropositionRepository $followPropositionRepository,
    ): Response
    {
        $user = $this->getUser();
        $unprocessedFollowPropositions = $followPropositionRepository->findUserOnesUnprocessed($user);
        $acceptedFollowPropositions = $followPropositionRepository->findUserOnesAccepted($user);
        $refusedFollowPropositions = $followPropositionRepository->findUserOnesRefused($user);

        return $this->render('my_account/follow-propositions.html.twig', [
            'user' => $user,
            'unprocessed_follow_propositions' => $unprocessedFollowPropositions,
            'accepted_follow_propositions' => $acceptedFollowPropositions,
            'refused_follow_propositions' => $refusedFollowPropositions,
        ]);
    }
}
