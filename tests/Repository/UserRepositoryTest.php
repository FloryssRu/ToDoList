<?php

namespace Tests\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testFindAnonymousUser(): void
    {
        self::bootKernel();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        $this->assertIsObject($user);
    }

    public function testFindAll(): void
    {
        self::bootKernel();

        $users = $this->entityManager->getRepository(User::class)->findAll();

        $this->assertGreaterThanOrEqual(3, $users);
    }
}