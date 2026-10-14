<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class PlaylistControllerTest extends WebTestCase
{
    public function testPlaylistsPageRedirectsToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseRedirects('/login');
    }

    public function testPlaylistsPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    public function testPlaylistEditPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);
        $playlistRepository = static::getContainer()->get(\App\Repository\PlaylistRepository::class);
        $playlist = $playlistRepository->findOneBy(['user' => $user]);
        $this->assertNotNull($playlist, 'Aucune playlist pour cet utilisateur en base de test');
        $client->request('GET', '/playlist/' . $playlist->getId() . '/edit');
        $this->assertResponseIsSuccessful();
    }
}
