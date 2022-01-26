<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    public function testCreateUser()
    {
        $user = (new User)
            ->setEmail("email@email.com")
            ->setUsername("username")
            ->setPassword("this_password_is_hashed")
        ;

        self::bootKernel();
        $errors = KernelTestCase::getContainer()->get(ValidatorInterface::class)->validate($user);
        $this->assertCount(0, $errors);
    }
}