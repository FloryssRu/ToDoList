<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserTest extends TestCase
{
    private $taskRepository;

    private $userRepository;

    private $entityManager;

    private $hasher;
    
    /** @var UserManager */
    private $entity;

    public function setUp(): void
    {
        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hasher = $this->createMock(PasswordHasherInterface::class);
        $this->entity = new UserManager(
            $this->taskRepository,
            $this->userRepository,
            $this->entityManager,
            $this->hasher
        );
    }

    public function testUserHasRoles()
    {
        $user = new User();
        $user
            ->setUsername('username')
            ->setEmail('email@email.com')
            ->setPassword('secret')
        ;

        $this->assertIsArray($user->getRoles());
    }

    public function testCreateUser()
    {
        $userAdmin = new User();
        $userAdmin
            ->setUsername('username')
            ->setEmail('email@email.com')
            ->setPassword($this->entity->hashPassword('secret'))
            ->setRoles(['ROLE_ADMIN'])
        ;

        $newUser = new User();
        $newUser
            ->setUsername('usernameNew')
            ->setEmail("newuser@email.com")
            ->setPassword($this->entity->hashPassword('secret'))
        ;

        $expected = clone $newUser;

        $actual = $this->entity->createUser($userAdmin, $newUser);

        $this->assertEquals($expected, $actual);
    }

    public function testGetUsers()
    {
        $user[0] = new User();
        $user[0]
            ->setUsername('username')
            ->setEmail("user@email.com")
            ->setPassword($this->entity->hashPassword('secret'))
        ;
        $this->userRepository->expects($this->any())->method("findAll")->willReturn($user);

        $expected[0] = clone $user[0];

        $actual = $this->entity->getUsers();

        $this->assertEquals($expected, $actual);
    }
}