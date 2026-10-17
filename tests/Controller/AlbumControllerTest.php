<?php
namespace App\Tests\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $client->request('GET', self::URL_ADMIN_ALBUM_NEW);
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

    public function testAddAlbumWithImage(): void
    {
        $client = static::createClient();
        $this->loginUser($client);

        // Récupérer un artiste valide en BDD
        $artistRepo = $this->getRepository(ArtistRepository::class);
        $artist = $artistRepo->findOneBy([]);

        // Créer un faux fichier JPEG avec GD
        $tempFile = tempnam(sys_get_temp_dir(), 'test_') . '.jpg';
        $image = imagecreatetruecolor(10, 10);
        imagejpeg($image, $tempFile);
        imagedestroy($image);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'cover_test.jpg',
            'image/jpeg',
            null,
            true // mode test
        );

        $client->request('GET', self::URL_ADMIN_ALBUM_NEW);
        $this->assertResponseIsSuccessful();

        $client->submitForm('Ajouter', [
            'album[title]'          => 'Test Album PHPUnit',
            'album[releaseDate]'    => '2024-01-01T00:00',
            'album[type]'           => 'album',
            'album[artist]'         => $artist->getId(),
            'album[coverImageFile]' => $uploadedFile,
        ]);

        $this->assertResponseRedirects();

        // Nettoyage — supprimer l'album créé
        $albumRepo = $this->getRepository(AlbumRepository::class);
        $album = $albumRepo->findOneBy(['title' => 'Test Album PHPUnit']);
        if ($album) {
            $this->removeEntity($album);
        }
    }
}