<?php

namespace App\Tests\Repository;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenreRepositoryTest extends KernelTestCase
{
    private GenreRepository $genreRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->genreRepository = self::getContainer()->get(GenreRepository::class);
    }

    public function testFindAllReturnsArray(): void
    {
        $genres = $this->genreRepository->findBy([], ['name' => 'ASC']);
        $this->assertIsArray($genres);
    }

    public function testGenresAreSortedAlphabetically(): void
    {
        $genres = $this->genreRepository->findBy([], ['name' => 'ASC']);
        $names = array_map(fn($g) => $g->getName(), $genres);
        $sorted = $names;
        sort($sorted);
        $this->assertSame($sorted, $names);
    }
}