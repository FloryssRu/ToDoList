<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}