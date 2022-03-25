<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\PrivacySettingsType;
use App\Form\UserNutritionalDataType;
use App\Form\UserType;
use App\FormDataObject\UserNutritionalDataFDO;
use App\Repository\NotificationReceiptRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/my-account")
 */
class MyAccountController extends AbstractController
{
    /**
     * @Route("/", name="my_account_index", methods={"GET"})
     * @Security("not is_anonymous()")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('my_account/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/edit-nutritional-data", name="my_account_edit_nutritional_data", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function editNutritionalData(Request $request, UserNutritionalDataFDO $userNutritionalDataFDO): Response
    {
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currentUser);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $currentUser->getId()]);
        }

        return $this->render('user/edit-nutritional-data.html.twig', [
            'user' => $currentUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-password", name="my_account_edit_password", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function editPassword(UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            if ($passwordHasher->isPasswordValid($user, $formData['old_password'], null)) {
                $hashedNewPassword = $passwordHasher->hashPassword($user, $formData['new_password']);
                $user->setPassword($hashedNewPassword);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('my_account_index');
        }

        return $this->render('my_account/edit-password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-privacy-settings", name="my_account_edit_privacy_settings", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function editPrivacySettings(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PrivacySettingsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('my_account_index');
        }

        return $this->render('my_account/privacy.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/notifications", name="my_account_notifications", methods={"GET"})
     * @Security("not is_anonymous()")
     */
    public function notifications(
        Request $request,
        NotificationReceiptRepository $notificationReceiptRepository
    ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PrivacySettingsType::class, $user);
        $form->handleRequest($request);
        $unreadNotificationReceipts = $notificationReceiptRepository->findUnreadByUser($user);
        $readNotificationReceipts = $notificationReceiptRepository->findReadByUser($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('my_account_index');
        }

        return $this->render('my_account/notifications.html.twig', [
            'user' => $user,
            'unread_notification_receipts' => $unreadNotificationReceipts,
            'read_notification_receipts' => $readNotificationReceipts,
        ]);
    }
}
