<?php

namespace Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    private KernelBrowser $client;

    private $entityManager;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = $this->client->getContainer()
        ->get('doctrine')
        ->getManager();
    }

    public function userLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => 'user@email.com']);

        $this->client->loginUser($testUser);
    }

    public function adminLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => 'admin@email.com']);

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

        $crawler = $this->client->clickLink('Consulter la liste des tâches à faire');

        $this->assertSelectorTextContains('h1', 'Liste des tâches');

        $crawler = $this->client->clickLink("La tâche d'un utilisateur classique");

        $this->assertSelectorTextContains('h1', "Modifier La tâche d'un utilisateur classique");

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

        $form = $crawler->filter('form')->selectButton('Marquer comme faite')->eq(5)->form();
        $crawler = $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorExists('div.alert.alert-success');
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

    // public function testViewUsersPagesWithSimpleUserLogin()
    // {
    //     $this->userLogin();

    //     $this->client->request('GET', '/users');
    //     $this->assertResponseStatusCodeSame(403);

    //     $this->client->request('GET', '/users/create');
    //     $this->assertResponseStatusCodeSame(403);

    //     $id = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'simple_user']);
    //     $this->client->request('GET', '/users//' . $id . '/edit');
    //     $this->assertResponseStatusCodeSame(403);
    // }

    // public function testViewUsersPagesWithAdminLogin()
    // {
    //     $this->adminLogin();

    //     $this->client->request('GET', '/users');
    //     $this->assertResponseStatusCodeSame(200);

    //     $this->client->request('GET', '/users/create');
    //     $this->assertResponseStatusCodeSame(200);

    //     $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'simple_user']);
    //     $this->client->request('GET', '/users//' . $user->getId() . '/edit');
    //     $this->assertResponseStatusCodeSame(200);
    // }

    // public function testCreateUserWithAdminLogin()
    // {
    //     $this->adminLogin();

    //     $this->client->request('GET', '/');
    //     $crawler = $this->client->clickLink('Créer un utilisateur');

    //     $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

    //     $form = $crawler->selectButton('Ajouter')->form([
    //         'user' => [
    //             'username' => 'Nouvel_utilisateur_de_test',
    //             'password' => [
    //                 'first' => 'secret',
    //                 'second' => 'secret'
    //             ],
    //             'email' => 'test@example.com',
    //             'roles_options' => 'ROLE_USER'
    //         ]
    //     ]);

    //     $this->client->submit($form);
    //     $this->client->followRedirect();

    //     $this->assertResponseStatusCodeSame(200);
    //     $this->assertSelectorExists('.alert.alert-success');
    //     $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    // }

    // public function testEditUserWithAdminLogin()
    // {
    //     $this->adminLogin();

    //     $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'simple_user_2_modify']);

    //     $crawler = $this->client->request('GET', '/users//' . $user->getId() . '/edit');

    //     $this->assertSelectorTextContains('h1', 'Modifier simple_user_2_modify');

    //     $form = $crawler->selectButton('Modifier')->form([
    //         'user' => [
    //             'username' => 'simple_user_2_modified',
    //             'password' => [
    //                 'first' => 'secret',
    //                 'second' => 'secret'
    //             ],
    //             'email' => 'mail_modifie@example.com',
    //             'roles_options' => 'ROLE_USER'
    //         ]
    //     ]);

    //     $this->client->submit($form);
    //     $this->client->followRedirect();

    //     $this->assertResponseStatusCodeSame(200);
    //     $this->assertSelectorExists('.alert.alert-success');
    //     $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    // }
}