<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserNutritionalDataType;
use App\FormDataObject\UserNutritionalDataFDO;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new(UserPasswordEncoderInterface $passwordEncoder, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($user->getPassword())) {
                $encryptedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encryptedPassword);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-nutritional-data", name="user_edit_nutritional_data", methods={"GET","POST"})
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
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(UserPasswordEncoderInterface $passwordEncoder, Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($user->getPassword())) {
                $encryptedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encryptedPassword);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
