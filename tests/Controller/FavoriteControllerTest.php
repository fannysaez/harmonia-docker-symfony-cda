<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class FavoriteControllerTest extends WebTestCase
{
    public function testFavoriteRedirectsToLoginWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favorite/131');
        $this->assertResponseRedirects('/login');
    }

    public function testFavoriteWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);
        $client->request('GET', '/favorite/131');
        $this->assertResponseRedirects();
    }
}
