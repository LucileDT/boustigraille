<?php

namespace App\Controller;

use App\Entity\Store;
use App\Form\StoreType;
use App\Repository\StoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/store')]
#[IsGranted('ROLE_ADMIN')]
class StoreController extends AbstractController
{
    #[Route('/', name: 'store_index', methods: ['GET'])]
    public function index(StoreRepository $storeRepository): Response
    {
        return $this->render('store/index.html.twig', [
            'stores' => $storeRepository->findBy([], ['sortNumber' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'store_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        StoreRepository $storeRepository): Response
    {
        $store = new Store();
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateRelatedStores($entityManager, $storeRepository, $store);
            $entityManager->persist($store);
            $entityManager->flush();

            return $this->redirectToRoute('store_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('store/new.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'store_show', methods: ['GET'])]
    public function show(Store $store): Response
    {
        return $this->render('store/show.html.twig', [
            'store' => $store,
        ]);
    }

    #[Route('/{id}/edit', name: 'store_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Store $editedStore,
        EntityManagerInterface $entityManager,
        StoreRepository $storeRepository): Response
    {
        $form = $this->createForm(StoreType::class, $editedStore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateRelatedStores($entityManager, $storeRepository, $editedStore);
            $store = $storeRepository->findOneBy(['id' => $editedStore->getId()]);
            $store->setSortNumber($editedStore->getSortNumber());
            $store->setLabel($editedStore->getLabel());
            $entityManager->persist($store);
            $entityManager->flush();

            return $this->redirectToRoute('store_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('store/edit.html.twig', [
            'store' => $editedStore,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'store_delete', methods: ['POST'])]
    public function delete(Request $request, Store $store, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$store->getId(), $request->request->get('_token'))) {
            $entityManager->remove($store);
            $entityManager->flush();
        }

        return $this->redirectToRoute('store_index', [], Response::HTTP_SEE_OTHER);
    }

    private function updateRelatedStores(
        EntityManagerInterface $entityManager,
        StoreRepository $storeRepository,
        Store $store
    )
    {
        // clear eventual stores waiting flushing to avoid interferences with existing ones
        $entityManager->clear();

        // check if a store already has this sort number
        $storeInThisPlace = $storeRepository->findOneBy(['sortNumber' => $store->getSortNumber()]);
        if (!empty($storeInThisPlace)) {
            // a store has the same sort number
            $storesNeedingUpdate = [
                $store->getSortNumber() => $storeInThisPlace,
            ];

            $hasLastStoreBeenFound = false;
            do {
                // update sort number to a higher one & check if the sort number is already used
                $numberSort = $storeInThisPlace->getSortNumber() + 1;
                $storeInThisPlace = $storeRepository->findOneBy(['sortNumber' => $numberSort]);
                if (empty($storeInThisPlace)) {
                    $hasLastStoreBeenFound = true;
                } else {
                    $storesNeedingUpdate[$numberSort] = $storeInThisPlace;
                }
            } while (!$hasLastStoreBeenFound);

            // update stores to avoid errors on unique constraint failure
            krsort($storesNeedingUpdate);
            foreach ($storesNeedingUpdate as $sortNumber => $storeNeedingUpdate) {
                $storeNeedingUpdate->setSortNumber($sortNumber + 1);
                $entityManager->persist($storeNeedingUpdate);
                $entityManager->flush();
            }
        }
    }
}
