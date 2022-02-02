<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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

    public function testListUsersWithoutLogin(): void
    {
        $this->client->request('GET', '/users');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateUserWithoutLogin(): void
    {
        $this->client->request('GET', '/users/create');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testEditUserWithoutLogin(): void
    {
        $this->client->request('GET', '/users/2/edit');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testViewUsersPagesWithSimpleUserLogin()
    {
        $this->userLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('GET', '/users/4/edit');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testViewUsersPagesWithAdminLogin()
    {
        $this->adminLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/users/4/edit');
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
        $this->adminLogin();

        $crawler = $this->client->request('GET', '/users/4/edit');

        $this->assertSelectorTextContains('h1', 'Modifier simple_user_2_modify');

        $form = $crawler->selectButton('Modifier')->form([
            'user' => [
                'username' => 'simple_user_2_modified',
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