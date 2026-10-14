<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\TrackRepository;

class FavoriteControllerTest extends WebTestCase
{
    public function testFavoriteRedirectsToLoginWhenNotLoggedIn(): void
    {
        $client = static::createClient();

        $trackRepository = static::getContainer()->get(TrackRepository::class);
        $track = $trackRepository->findOneBy([]);
        $this->assertNotNull($track, 'Aucun track en base de test');

        $client->request('GET', '/favorite/' . $track->getId());
        $this->assertResponseRedirects('/login');
    }

    public function testFavoriteWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $trackRepository = static::getContainer()->get(TrackRepository::class);
        $track = $trackRepository->findOneBy([]);
        $this->assertNotNull($track, 'Aucun track en base de test');

        $client->request('GET', '/favorite/' . $track->getId());
        $this->assertResponseRedirects();
    }
}
