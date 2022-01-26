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

    public function testCountAllTasks(): void
    {
        self::bootKernel();

        $tasks = $this->entityManager->getRepository(User::class)->findAll([]);

        $this->assertEquals(3, count($tasks));
    }
}