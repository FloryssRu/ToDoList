<?php

namespace Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function userLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(["email" => 'user@email.com']);

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
    }

    public function testViewHomepage()
    {
        $this->userLogin();

        $this->client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    // Tasks ---------

    public function testCreateTask()
    {
        $this->userLogin();

        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task' => [
                'title' => 'Une nouvelle tâche',
                'content' => 'pour tester testCreateTask()'
            ]
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');

        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
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