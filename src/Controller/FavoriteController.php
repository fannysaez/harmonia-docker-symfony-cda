<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class FavoriteController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/favorites', name: 'app_favorites')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('favorite/index.html.twig', [
            'tracks' => $user->getFavoriteTracks(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/favorite/{track_id}', name: 'app_favorite')]
    public function toggle(int $track_id, TrackRepository $trackRepository, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $track = $trackRepository->find($track_id);
        if (!$track) {
            throw $this->createNotFoundException('Track non trouvé');
        }

        if ($user->getFavoriteTracks()->contains($track)) {
            $user->removeFavoriteTrack($track);
        } else {
            $user->addFavoriteTrack($track);
        }

        $em->flush();

        return $this->redirectToRoute('app_album_show', ['id' => $track->getAlbum()->getId()]);
    }
}