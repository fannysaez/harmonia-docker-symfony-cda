<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

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
        $client->request('GET', '/add-track/31');
        $this->assertResponseIsSuccessful();
    }

    public function testTrackEditPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);
        $client->request('GET', '/edit-track/131');
        $this->assertResponseIsSuccessful();
    }

    public function testTrackListenPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/track/131/listen');
        $this->assertResponseRedirects();
    }
}
