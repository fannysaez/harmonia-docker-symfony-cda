<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

final class AlbumController extends AbstractController
{
    #[Route('/album', name: 'app_albums')]
    public function index(AlbumRepository $albumRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $limit = 4;

        $albums = $paginator->paginate(
            $albumRepository->findByTypeQuery('album'),
            $request->query->getInt('pageAlbum', 1),
            $limit,
            ['pageParameterName' => 'pageAlbum']
        );

        $EP = $paginator->paginate(
            $albumRepository->findByTypeQuery('EP'),
            $request->query->getInt('pageEP', 1),
            $limit,
            ['pageParameterName' => 'pageEP']
        );

        $singles = $paginator->paginate(
            $albumRepository->findByTypeQuery('single'),
            $request->query->getInt('pageSingle', 1),
            $limit,
            ['pageParameterName' => 'pageSingle']
        );

        return $this->render('album/albums.html.twig', [
            'albums'  => $albums,
            'EP'      => $EP,
            'singles' => $singles,
        ]);
    }

    #[Route('/album/search', name: 'app_album_search')]
    public function search(AlbumRepository $albumRepository, Request $request): Response
    {
        $search = $request->query->get('search', '');
        $albums = $albumRepository->findByTitleLike($search);
        return $this->json(['albums' => $albums], 200, [], ['groups' => ['album:read']]);
    }

    #[Route('/album/{id}', name: 'app_album_show')]
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/album-add', name: 'app_album_add')]
    public function add(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $coverFile */
            $coverFile = $form->get('coverImageFile')->getData();

            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

                $coverFile->move(
                    $this->getParameter('covers_directory'),
                    $newFilename
                );

                $album->setCoverImage($newFilename);
            }

            $album->setCreatedAt(new \DateTimeImmutable());
            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute('app_albums');
        }

        return $this->render('album/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/album-edit/{id}', name: 'app_album_edit')]
    public function edit(int $id, AlbumRepository $albumRepository, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $album = $albumRepository->find($id);
        if ($album === null) {
            return $this->redirectToRoute('app_albums');
        }

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $coverFile */
            $coverFile = $form->get('coverImageFile')->getData();

            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

                $coverFile->move(
                    $this->getParameter('covers_directory'),
                    $newFilename
                );

                $album->setCoverImage($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('app_albums');
        }

        return $this->render('album/edit.html.twig', [
            'form'  => $form->createView(),
            'album' => $album,
        ]);
    }
}