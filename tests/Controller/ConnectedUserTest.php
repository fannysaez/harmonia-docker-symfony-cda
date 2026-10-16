<?php
namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class ConnectedUserTest extends AbstractWebTestCase
{
    public function testProfilAccessibleWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/profil');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminAccessibleWhenAdmin(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }

    public function testLogoutRedirects(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/logout');
        $this->assertResponseRedirects();
    }
}