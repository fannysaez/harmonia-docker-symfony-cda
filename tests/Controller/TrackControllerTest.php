<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\TrackRepository;
use App\Repository\AlbumRepository;

class TrackControllerTest extends WebTestCase
{
    public function testAdminTracksPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);
        $client->request('GET', '/admin/tracks');
        $this->assertResponseIsSuccessful();
    }

    public function testAddTrackPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $albumRepository = static::getContainer()->get(AlbumRepository::class);
        $album = $albumRepository->findOneBy([]);
        $this->assertNotNull($album, 'Aucun album en base de test');

        $client->request('GET', '/add-track/' . $album->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testTrackEditPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $trackRepository = static::getContainer()->get(TrackRepository::class);
        $track = $trackRepository->findOneBy([]);
        $this->assertNotNull($track, 'Aucun track en base de test');

        $client->request('GET', '/edit-track/' . $track->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testTrackListenPage(): void
    {
        $client = static::createClient();

        $trackRepository = static::getContainer()->get(TrackRepository::class);
        $track = $trackRepository->findOneBy([]);
        $this->assertNotNull($track, 'Aucun track en base de test');

        $client->request('POST', '/track/' . $track->getId() . '/listen');
        $this->assertResponseRedirects();
    }
}
