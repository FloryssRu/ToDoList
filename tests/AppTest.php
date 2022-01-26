<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    public function testViewHomepage()
    {
        // on se connecte en tant qu'user classique

        // on arrive bien sur la homepage avec un statut 200
        // $this->assertResponseStatusCodeSame(200);
        // $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    // Tasks ---------

    public function testCreateTask()
    {
        // on se connecte en tant qu'user classique
        // on clique sur "créer une tâche"
        // on crée la tâche
        // on vérifie que tout est bon
    }

    public function testEditTask()
    {
        // on se connecte en tant qu'user classique
        // on clique sur la liste des tâches
        // on clique sur une tâche à modifier
        // on la modifie
        // on vérifie que tout est bon
    }

    public function testToggleTask()
    {
        // on se connecte en tant qu'user classique
        // on clique sur la liste des tâches
        // on clique sur "tâche faite"
        // on vérifie que tout est bon
    }

    public function testDeleteTask()
    {
        // on se connecte en tant qu'user classique
        // on clique sur la liste des tâches
        // on clique sur "supprimer la tâche" (elle nous appartient)
        // on vérifie que tout est bon
    }

    public function testDeleteTaskByAnotherUser()
    {
        // on se connecte en tant qu'user classique
        // on clique sur la liste des tâches
        // on clique sur "supprimer la tâche" (elle ne nous appartient pas)
        // on vérifie que tout est bon
    }

    public function testDeleteTaskByAnonymousUserWithSimpleUserLogin()
    {
        // on se connecte en tant qu'user classique
        // on clique sur la liste des tâches
        // on clique sur "supprimer la tâche" (elle appartient à l'user anonymous)
        // on vérifie que tout est bon
    }

    public function testDeleteTaskByAnonymousUserWithAdminLogin()
    {
        // on se connecte en tant qu'user admin
        // on clique sur la liste des tâches
        // on clique sur "supprimer la tâche" (elle appartient à l'user anonymous)
        // on vérifie que tout est bon
    }

    // User -------

    public function testViewUsersPagesWithSimpleUserLogin()
    {
        // on se connecte en tant qu'user classique
        // on va à l'url "/users"
        // on va à l'url "/users/create"
        // on va à l'url "/users/1/edit"
        // on vérifie qu'on a bien une 403 à chaque fois
    }

    public function testCreateUserWithAdminLogin()
    {
        // on se connecte en tant qu'user admin
        // on clique sur "créer un utilisateur"
        // on remplit et valide le form
        // on vérifie que tout est bon
    }

    public function testEditUserWithAdminLogin()
    {
        // on se connecte en tant qu'user admin
        // on va sur la liste des utilisateurs
        // on clique sur un lien pour modifier qqn
        // on remplit et valide le form
        // on vérifie que tout est bon
    }
}