<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class PageAccessTest extends AbstractWebTestCase
{
    public function testHomePageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testAlbumsPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/album');
        $this->assertResponseIsSuccessful();
    }

    public function testArtistsPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/artist');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testRegisterPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
    }
}