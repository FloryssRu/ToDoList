<?php

namespace Tests\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testFindAll(): void
    {
        self::bootKernel();

        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        $this->assertGreaterThanOrEqual(6, $tasks);
    }
}