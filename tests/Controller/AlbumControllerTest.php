<?php
namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AlbumControllerTest extends AbstractWebTestCase
{
    public function testAlbumListPage(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ALBUM);
        $this->assertResponseIsSuccessful();
    }

    public function testAlbumShowPage(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ALBUM . '/121');
        $this->assertResponseIsSuccessful();
    }

    public function testAlbumAddPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', '/album-add');
        $this->assertResponseIsSuccessful();
    }

    #[TestWith([121, 'Exercitationem est error.', 'EP'])]
    #[TestWith([122, 'Sapiente nihil illum eum.', 'single'])]
    #[TestWith([123, 'Vel eos enim corrupti.', 'album'])]
    #[TestWith([124, 'Et illum saepe unde.', 'single'])]
    #[TestWith([125, 'Sint nulla sequi.', 'EP'])]
    public function testAlbumShowPageWithDetails(int $albumId, string $title, string $type): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_ALBUM . '/' . $albumId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $title);
        $this->assertSelectorTextContains('span.badge.bg-secondary', $type);
    }
}