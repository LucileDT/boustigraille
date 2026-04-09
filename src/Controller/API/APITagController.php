<?php

namespace App\Controller\API;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/tag', name: 'api_tag_')]
class APITagController extends AbstractController
{
    #[Route(path: '/', name: 'list', methods: ['GET'])]
    public function getRecipesList(
        TagRepository $tagRepository,
    ): JsonResponse
    {
        return new JsonResponse($tagRepository->findAll());
    }
}
