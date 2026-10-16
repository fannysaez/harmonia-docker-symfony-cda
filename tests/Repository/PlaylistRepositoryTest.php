<?php
namespace App\Tests\Repository;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    private PlaylistRepository $playlistRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->playlistRepository = self::getContainer()->get(PlaylistRepository::class);
    }

    public function testFindAllReturnsArray(): void
    {
        $playlists = $this->playlistRepository->findAll();
        $this->assertIsArray($playlists);
    }

    public function testFindByReturnsArray(): void
    {
        $playlists = $this->playlistRepository->findBy([], ['id' => 'ASC']);
        $this->assertIsArray($playlists);
    }

    public function testFindOneByReturnsPlaylistOrNull(): void
    {
        $playlist = $this->playlistRepository->findOneBy([]);
        $this->assertTrue($playlist === null || $playlist->getId() > 0);
    }
}