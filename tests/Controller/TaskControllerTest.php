<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListTasksWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testListTasksWithLogin(): void
    // {
    //     // ...
    // }

    public function testCreateTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/create');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testCreateTaskWithLogin(): void
    // {
    //     // ...
    // }

    public function testEditTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/1/edit');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testEditTaskWithLogin(): void
    // {
    //     // ...
    // }

    public function testToogleTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/1/toggle');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testToogleTaskWithLogin(): void
    // {
    //     // ...
    // }

    public function testDeleteTaskWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/1/delete');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testDeleteTaskWithLogin(): void
    // {
    //     // ...
    // }
}
