<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Generator;
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

    /**
     * @dataProvider provideInvalidCredentials
     * @param array $formData
     */
    public function testTryToLoginWithInvalidCredentials(array $formData)
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form($formData);

        $this->client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');

        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
    }

    /**
     * @return Generator
     */
    public function provideInvalidCredentials(): Generator
    {
        yield [[
            "_username" => "simple_user",
            "_password" => "fail"
        ]];

        yield [[
            "_username" => "fail",
            "_password" => "secret"
        ]];

        yield [[
            "_username" => "",
            "_password" => "secret"
        ]];

        yield [[
            "_username" => "simple_user",
            "_password" => ""
        ]];

        yield [[
            "_username" => "",
            "_password" => ""
        ]];
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
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de g??rer l'ensemble de vos t??ches sans effort !");
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
