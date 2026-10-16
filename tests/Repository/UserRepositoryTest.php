<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    public function testKernelBoots(): void
    {
        self::bootKernel();
        $this->assertTrue(true);
    }

    public function testFindAdminUser(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'fanny.saez.0486@gmail.com']);
        $this->assertNotNull($user);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testFindRegularUser(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'lourdes.sawayn@example.net']);
        $this->assertNotNull($user);
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testFindNonExistentUser(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'inexistant@example.com']);
        $this->assertNull($user);
    }
}