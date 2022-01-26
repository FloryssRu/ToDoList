<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');
        
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Connectez-vous');
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testTryToLoginWithInvalidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'username',
            '_password' => 'badPassword'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');

        $client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testTryToLoginWithValidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Floryss',
            '_password' => 'secret'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/');

        $client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }
}