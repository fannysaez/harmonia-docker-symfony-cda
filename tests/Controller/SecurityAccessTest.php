<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class SecurityAccessTest extends AbstractWebTestCase
{
    public function testProfilRedirectsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profil');
        $this->assertResponseRedirects('/login');
    }

    public function testFavoritesRedirectsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favorites');
        $this->assertResponseRedirects('/login');
    }

    public function testAdminRedirectsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseRedirects('/login');
    }

    public function testAdminRedirectsIfNotAdmin(): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'lourdes.sawayn@example.net');
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403);
    }
}