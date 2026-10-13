<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testAdminRedirectsToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseRedirects('/login');
    }

    public function testAdminPageWhenLoggedInAsAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }
    public function testAdminAlbumsPageWhenLoggedIn(): void
{
    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
    $client->loginUser($user);
    $client->request('GET', '/admin/albums');
    $this->assertResponseIsSuccessful();
}

public function testAdminTracksPageWhenLoggedIn(): void
{
    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
    $client->loginUser($user);
    $client->request('GET', '/admin/tracks');
    $this->assertResponseIsSuccessful();
}

public function testAdminPlaylistsPageWhenLoggedIn(): void
{
    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
    $client->loginUser($user);
    $client->request('GET', '/admin/playlists');
    $this->assertResponseIsSuccessful();
}

public function testAdminArtistsPageWhenLoggedIn(): void
{
    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
    $client->loginUser($user);
    $client->request('GET', '/admin/artists');
    $this->assertResponseIsSuccessful();
}

public function testAdminGenresPageWhenLoggedIn(): void
{
    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
    $client->loginUser($user);
    $client->request('GET', '/admin/genres');
    $this->assertResponseIsSuccessful();
}

public function testAdminUsersPageWhenLoggedIn(): void
{
    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);
    $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
    $client->loginUser($user);
    $client->request('GET', '/admin/users');
    $this->assertResponseIsSuccessful();
}
}
