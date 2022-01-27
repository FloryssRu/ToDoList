<?php

namespace Tests;

use App\Entity\Task;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    public function adminLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(["email" => 'admin@email.com']);

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
        $this->userLogin();

        $crawler = $this->client->request('GET', '/');

        // on clique sur la liste des tâches
        $crawler = $this->client->clickLink('Consulter la liste des tâches à faire');

        $this->assertSelectorTextContains('h1', 'Liste des tâches');

        // on clique sur une tâche à modifier
        $crawler = $this->client->clickLink("La tâche d'un utilisateur classique");

        $this->assertSelectorTextContains('h1', "Modifier La tâche d'un utilisateur classique");

        // on la modifie
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => "La tâche d'un utilisateur classique",
            'task[content]' => "Elle n'est pas si difficile"
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
    }

    public function testToggleTask()
    {
        $this->userLogin();

        $this->client->request('GET', '/');

        // on clique sur la liste des tâches
        $crawler = $this->client->clickLink('Consulter la liste des tâches à faire');

        $this->assertSelectorTextContains('h1', 'Liste des tâches');

        // - on clique sur LE toggle de la page qui est dans un <form action="/tasks/5/toggle">
        // $form = $crawler->filter('form')->selectButton(' Marquer comme faite ')->form();
        // $crawler = $this->client->submit($form);
    }

    public function testDeleteTask()
    {
        // même problème que pour toggle
    }

    public function testDeleteTaskByAnotherUser()
    {
        // même problème que pour toggle
    }

    public function testDeleteTaskByAnonymousUserWithSimpleUserLogin()
    {
        // même problème que pour toggle
    }

    public function testDeleteTaskByAnonymousUserWithAdminLogin()
    {
        // même problème que pour toggle
    }

    // User -------

    public function testViewUsersPagesWithSimpleUserLogin()
    {
        $this->userLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('GET', '/users/2/edit');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testViewUsersPagesWithAdminLogin()
    {
        $this->adminLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/users/2/edit');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateUserWithAdminLogin()
    {
        $this->adminLogin();

        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer un utilisateur');

        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

        $form = $crawler->selectButton('Ajouter')->form([
            'user' => [
                'username' => 'Nouvel_utilisateur_de_test',
                'password' => [
                    'first' => 'secret',
                    'second' => 'secret'
                ],
                'email' => 'test@example.com',
                'roles_options' => 'ROLE_USER'
            ]
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testEditUserWithAdminLogin()
    {
        // on se connecte en tant qu'user admin
        $this->adminLogin();

        $crawler = $this->client->request('GET', '/users/3/edit');

        $this->assertSelectorTextContains('h1', 'Modifier simple_user');

        $form = $crawler->selectButton('Ajouter')->form([
            'user' => [
                'username' => 'simple_user',
                'password' => [
                    'first' => 'secret',
                    'second' => 'secret'
                ],
                'email' => 'mail_modifie@example.com',
                'roles_options' => 'ROLE_USER'
            ]
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }
}