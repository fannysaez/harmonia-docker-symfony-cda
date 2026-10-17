<?php

namespace App\Tests;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractKernelTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function getRepository(string $repoClass): ServiceEntityRepository
    {
        return static::getContainer()->get($repoClass);
    }
}