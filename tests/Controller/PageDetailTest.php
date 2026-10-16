<?php
namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class PageDetailTest extends AbstractWebTestCase
{
    // ---- tests originaux ----

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

    // ---- dataProvider ----

    public static function detailPagesProvider(): array
    {
        return [
            'album 128'  => ['/album/128', 200],
            'artist 111' => ['/artist/111', 200],
            'page 404'   => ['/page-qui-nexiste-pas', 404],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('detailPagesProvider')]
    public function testDetailPageReturnsExpectedStatus(string $url, int $status): void
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertResponseStatusCodeSame($status);
    }
}