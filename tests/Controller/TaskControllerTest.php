<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListTasksWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/create');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testEditTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/1/edit');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testToogleTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/1/toggle');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testDeleteTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/1/delete');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }
}
