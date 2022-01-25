<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setUsername("anonymous")
            ->setPassword('$2y$13$GUOn1T2EPB3XjDS05uJJY.DwFgi6nEfeGa3d6iKb6MdPmocYQorUW')
            ->setEmail("anonymous@email.com")
        ;
        $manager->persist($user);

        $useradmin = new User();
        $useradmin
            ->setUsername("admin")
            ->setPassword('$2y$13$mNGve1XEbROA5F5WVt2Sb.RTPPED24sgkHp3TvzSKJazPQB/8hONi')
            ->setEmail("admin@email.com")
            ->setRoles(["ROLE_ADMIN"])
        ;
        $manager->persist($useradmin);

        $simpleuser = new User();
        $simpleuser
            ->setUsername("simple_user")
            ->setPassword('$2y$13$4SIFFYxzfVYr1DAVYBHBZ.h1.dmULX2XzjDMtodVrFX.klFXHiqDu')
            ->setEmail("user@email.com")
        ;
        $manager->persist($simpleuser);

        $task1 = new Task();
        $task1
            ->setTitle("Première tâche")
            ->setContent("J'appartiens à l'utilisateur anonyme")
            ->setAuthor($user)
        ;
        $manager->persist($task1);

        $task2 = new Task();
        $task2
            ->setTitle("Deuxième tâche")
            ->setContent("J'appartiens aussi à l'utilisateur anonyme")
            ->setAuthor($user)
        ;
        $manager->persist($task2);

        $task3 = new Task();
        $task3
            ->setTitle("Tâche facile")
            ->setContent("Créée par l'administrateur")
            ->setAuthor($useradmin)
        ;
        $manager->persist($task3);

        $task4 = new Task();
        $task4
            ->setTitle("Une autre tâche")
            ->setContent("Signé : l'administrateur")
            ->setAuthor($useradmin)
        ;
        $manager->persist($task4);

        $task5 = new Task();
        $task5
            ->setTitle("La tâche d'un utilisateur classique")
            ->setContent("Elle est difficile")
            ->setAuthor($simpleuser)
        ;
        $manager->persist($task5);

        $task6 = new Task();
        $task6
            ->setTitle("Tâche")
            ->setContent('A faire pour demain /!\ signé: utilisateur classique')
            ->setAuthor($simpleuser)
        ;
        $manager->persist($task6);

        $manager->flush();
    }
}
