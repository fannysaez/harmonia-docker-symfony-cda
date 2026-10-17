<?php

namespace App\Tests\Controller\Admin;

use App\Tests\AbstractWebTestCase;

class AdminControllerTest extends AbstractWebTestCase
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
        $this->loginUser($client);
        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminAlbumsPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/admin/albums');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminTracksPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/admin/tracks');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminPlaylistsPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/admin/playlists');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminArtistsPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/admin/artists');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminGenresPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/admin/genres');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminUsersPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/admin/users');
        $this->assertResponseIsSuccessful();
    }
}
