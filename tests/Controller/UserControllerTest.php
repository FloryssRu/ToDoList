<?php

namespace Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListUsersWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testListUsersWithSimpleUserLogin(): void
    // {
    //     $simpleUser = (new User)
    //         ->setUsername('Test')
    //         ->setEmail('test@email.com')
    //         ->setRoles(["ROLE_USER"])
    //         ->setPassword('$2y$13$wIav05dY4083Mdh5nsNujuErOqds9.Dps4ZiQIlunZzgiL4iUpVsa')
    //     ;

    //     // connexion

    //     $client = static::createClient();

    //     $client->request('GET', '/users');
    //     $response = $client->getResponse();

    //     $this->assertEquals(403, $response->getStatusCode());
    // }

    // public function testListUsersWithAdminLogin(): void
    // {
    //     $adminUser = (new User)
    //         ->setUsername('TestAdmin')
    //         ->setEmail('test@email.com')
    //         ->setRoles(["ROLE_ADMIN"])
    //         ->setPassword('$2y$13$wIav05dY4083Mdh5nsNujuErOqds9.Dps4ZiQIlunZzgiL4iUpVsa')
    //     ;

    //     // connexion admin

    //     $client = static::createClient();

    //     $client->request('GET', '/users');
    //     $response = $client->getResponse();

    //     $this->assertEquals(200, $response->getStatusCode());
    // }

    // Create -----------------

    public function testCreateUserWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/create');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testCreateUserWithSimpleUserLogin(): void
    // {
    //     $simpleUser = (new User)
    //         ->setUsername('Test')
    //         ->setEmail('test@email.com')
    //         ->setRoles(["ROLE_USER"])
    //         ->setPassword('$2y$13$wIav05dY4083Mdh5nsNujuErOqds9.Dps4ZiQIlunZzgiL4iUpVsa')
    //     ;

    //     // connexion simple user

    //     $client = static::createClient();

    //     $client->request('GET', '/users/create');
    //     $response = $client->getResponse();

    //     $this->assertEquals(403, $response->getStatusCode());
    // }

    // public function testCreateUserWithAdminLogin(): void
    // {
    //     $adminUser = (new User)
    //         ->setUsername('TestAdmin')
    //         ->setEmail('test@email.com')
    //         ->setRoles(["ROLE_ADMIN"])
    //         ->setPassword('$2y$13$wIav05dY4083Mdh5nsNujuErOqds9.Dps4ZiQIlunZzgiL4iUpVsa')
    //     ;

    //     // connexion admin

    //     $client = static::createClient();

    //     $client->request('GET', '/users/create');
    //     $response = $client->getResponse();

    //     $this->assertEquals(200, $response->getStatusCode());
    // }

    // Edit -----------------

    public function testEditUserWithoutLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/2/edit');
        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // public function testEditUserWithSimpleUserLogin(): void
    // {
    //     $simpleUser = (new User)
    //         ->setUsername('Test')
    //         ->setEmail('test@email.com')
    //         ->setRoles(["ROLE_USER"])
    //         ->setPassword('$2y$13$wIav05dY4083Mdh5nsNujuErOqds9.Dps4ZiQIlunZzgiL4iUpVsa')
    //     ;

    //     // connexion simple user

    //     $client = static::createClient();

    //     $client->request('GET', '/users/2/edit');
    //     $response = $client->getResponse();

    //     $this->assertEquals(403, $response->getStatusCode());
    // }

    // public function testEditUserWithAdminLogin(): void
    // {
    //     $adminUser = (new User)
    //         ->setUsername('TestAdmin')
    //         ->setEmail('test@email.com')
    //         ->setRoles(["ROLE_ADMIN"])
    //         ->setPassword('$2y$13$wIav05dY4083Mdh5nsNujuErOqds9.Dps4ZiQIlunZzgiL4iUpVsa')
    //     ;

    //     // connexion admin

    //     $client = static::createClient();

    //     $client->request('GET', '/users/2/edit');
    //     $response = $client->getResponse();

    //     $this->assertEquals(200, $response->getStatusCode());
    // }
}