<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');

        //$response = $client->getResponse();
        //$this->assertEquals(302, $response->getStatusCode());
    }
}