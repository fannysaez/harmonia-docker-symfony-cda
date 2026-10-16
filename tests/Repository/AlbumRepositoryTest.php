<?php
namespace App\Tests\Repository;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumRepositoryTest extends KernelTestCase
{
    private AlbumRepository $albumRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->albumRepository = self::getContainer()->get(AlbumRepository::class);
    }

    // ---- tests originaux ----

    public function testFindByTitleLikeReturnsResults(): void
    {
        $results = $this->albumRepository->findByTitleLike('a');
        $this->assertIsArray($results);
    }

    public function testFindByTitleLikeWithUnknownTitle(): void
    {
        $results = $this->albumRepository->findByTitleLike('zzzzzzzzzz');
        $this->assertCount(0, $results);
    }

    public function testCountByTypeReturnsInteger(): void
    {
        $count = $this->albumRepository->countByType('album');
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindLastAddedReturnsMaxFive(): void
    {
        $results = $this->albumRepository->findLastAdded(5);
        $this->assertIsArray($results);
        $this->assertLessThanOrEqual(5, count($results));
    }

    // ---- dataProvider findByTitleLike ----

    public static function titleSearchProvider(): array
    {
        return [
            'lettre a'       => ['a'],
            'mot dark'       => ['dark'],
            'vide'           => [''],
            'aucun résultat' => ['zzzzzzzzzz'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('titleSearchProvider')]
    public function testFindByTitleLikeWithProvider(string $query): void
    {
        $results = $this->albumRepository->findByTitleLike($query);
        $this->assertIsArray($results);
    }

    // ---- tests findAlbumsLike ----

    public function testFindAlbumsLikeReturnsArray(): void
    {
        $results = $this->albumRepository->findAlbumsLike('a');
        $this->assertIsArray($results);
    }

    public function testFindAlbumsLikeWithSpaceInQuery(): void
    {
        $results = $this->albumRepository->findAlbumsLike('dark side');
        $this->assertIsArray($results);
    }

    public function testFindAlbumsLikeWithNoResults(): void
    {
        $results = $this->albumRepository->findAlbumsLike('zzzzzzzzzz');
        $this->assertCount(0, $results);
    }
}