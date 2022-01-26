<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListUsersWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateUserWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/create');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testEditUserWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/2/edit');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }
}