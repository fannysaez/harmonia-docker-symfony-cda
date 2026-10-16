<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class PageDetailTest extends AbstractWebTestCase
{
    public function testAlbumShowPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/album/128');
        $this->assertResponseIsSuccessful();
    }

    public function testArtistShowPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/artist/111');
        $this->assertResponseIsSuccessful();
    }

    public function testNotFoundPageReturns404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/page-qui-nexiste-pas');
        $this->assertResponseStatusCodeSame(404);
    }
}