<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class AjaxTestController extends AbstractController
{
    #[Route('/ajax/test', name: 'app_ajax_test')]
    public function index(): JsonResponse
    {
        return $this->json('retour en json !!!');
    }
}