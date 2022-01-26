<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskTest extends KernelTestCase
{
    public function testCreateTask()
    {
        $task = (new Task())
            ->setTitle("Titre de test")
            ->setContent("Contenu")
        ;
        
        $user = (new User)
            ->setEmail("email@email.com")
            ->setUsername("username")
            ->setPassword("this_password_is_hashed")
        ;

        $task->setAuthor($user);

        self::bootKernel();
        $errors = KernelTestCase::getContainer()->get(ValidatorInterface::class)->validate($task);
        $this->assertCount(0, $errors);

        $this->assertEquals($task->getAuthor(), $user);
    }
}