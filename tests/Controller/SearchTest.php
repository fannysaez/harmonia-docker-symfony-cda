<?php
namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class SearchTest extends AbstractWebTestCase
{
    public function testSearchReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/album/search', ['search' => 'a']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testSearchWithEmptyQueryReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/album/search');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testSearchReturnsAlbumsKey(): void
    {
        $client = static::createClient();
        $client->request('GET', '/album/search', ['search' => 'a']);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('albums', $data);
    }
}