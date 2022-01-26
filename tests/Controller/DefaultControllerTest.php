<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testIndexWithLogin(): void
    // {
    //     // connexion

    //     $client = static::createClient();

    //     $client->request('GET', '/');
    //     $response = $client->getResponse();

    //     $this->assertEquals(200, $response->getStatusCode());
    // }
}