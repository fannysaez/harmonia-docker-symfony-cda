<?php

namespace App\Tests\Repository;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArtistRepositoryTest extends KernelTestCase
{
    private ArtistRepository $artistRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->artistRepository = self::getContainer()->get(ArtistRepository::class);
    }

    public function testFindAllReturnsArray(): void
    {
        $artists = $this->artistRepository->findAll();
        $this->assertIsArray($artists);
    }

    public function testArtistsAreSortedAlphabetically(): void
    {
        $artists = $this->artistRepository->findBy([], ['stageName' => 'ASC']);
        $names = array_map(fn($a) => $a->getStageName(), $artists);
        $sorted = $names;
        sort($sorted);
        $this->assertSame($sorted, $names);
    }

    public function testFindOneByReturnsArtistOrNull(): void
    {
        $artist = $this->artistRepository->findOneBy(['stageName' => 'Genesis']);
        $this->assertTrue($artist === null || $artist->getStageName() === 'Genesis');
    }
}