<?php
namespace App\Tests\Repository;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrackRepositoryTest extends KernelTestCase
{
    private TrackRepository $trackRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->trackRepository = self::getContainer()->get(TrackRepository::class);
    }

    public function testFindAllReturnsArray(): void
    {
        $tracks = $this->trackRepository->findAll();
        $this->assertIsArray($tracks);
    }

    public function testFindByReturnsArray(): void
    {
        $tracks = $this->trackRepository->findBy([], ['id' => 'ASC']);
        $this->assertIsArray($tracks);
    }

    public function testFindOneByReturnsTrackOrNull(): void
    {
        $track = $this->trackRepository->findOneBy([]);
        $this->assertTrue($track === null || $track->getId() > 0);
    }
}