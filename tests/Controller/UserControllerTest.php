<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testList(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }
}