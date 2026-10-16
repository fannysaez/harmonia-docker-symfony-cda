<?php
namespace App\Tests\Repository;

use App\Repository\ListeningHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ListeningHistoryRepositoryTest extends KernelTestCase
{
    private ListeningHistoryRepository $listeningHistoryRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->listeningHistoryRepository = self::getContainer()->get(ListeningHistoryRepository::class);
    }

    public function testFindAllReturnsArray(): void
    {
        $history = $this->listeningHistoryRepository->findAll();
        $this->assertIsArray($history);
    }

    public function testFindByReturnsArray(): void
    {
        $history = $this->listeningHistoryRepository->findBy([], ['id' => 'ASC']);
        $this->assertIsArray($history);
    }

    public function testFindOneByReturnsHistoryOrNull(): void
    {
        $entry = $this->listeningHistoryRepository->findOneBy([]);
        $this->assertTrue($entry === null || $entry->getId() > 0);
    }
}