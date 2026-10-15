<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AjaxTestController extends AbstractController
{
    #[Route('/ajax', name: 'app_ajax')]
    public function page(): Response
    {
        return $this->render('ajax_test/index.html.twig');
    }

    #[Route('/ajax/test', name: 'app_ajax_test')]
    public function index(): JsonResponse
    {
        return $this->json('retour en json !!!');
    }

    #[Route('/ajax/test_2', name: 'app_ajax_test_2')]
    public function test2(): JsonResponse
    {
        $nb = 85;
        return $this->json(['nb' => $nb]);
    }

    #[Route('/ajax/count_genre', name: 'app_ajax_count_genre')]
    public function countGenre(GenreRepository $genreRepository): JsonResponse
    {
        $entities = $genreRepository->findAll();
        $total = count($entities);
        return $this->json(['total_genres' => $total]);
    }


    #[Route('/ajax/check_pokemon_genre', name: 'app_ajax_check_pokemon_genre')]
    public function checkPokemonGenre(GenreRepository $genreRepository): JsonResponse
    {
        $genres = $genreRepository->findByNameLike('pokemon');
        $found = false;
        if (count($genres) > 0) {
            $found = true;
        }
        return $this->json(['is_pokemon_exist' => $found]);
    }
}
