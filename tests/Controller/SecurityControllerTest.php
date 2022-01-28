<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function userLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => 'user@email.com']);

        $this->client->loginUser($testUser);
    }

    public function testLogin(): void
    {
        $this->client->request('GET', '/login');
        
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Connectez-vous');
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testTryToLoginWithInvalidCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'username',
            '_password' => 'badPassword'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');

        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testTryToLoginWithValidCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'simple_user',
            '_password' => 'secret'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('http://localhost/');

        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    public function testLogout()
    {
        $this->userLogin();

        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Connectez-vous');
    }
}