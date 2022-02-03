<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    private $entityManager;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = $this->client->getContainer()
        ->get('doctrine')
        ->getManager();
    }

    public function userLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => 'user@email.com']);

        $this->client->loginUser($testUser);
    }

    public function adminLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => 'admin@email.com']);

        $this->client->loginUser($testUser);
    }

    public function testListUsersWithoutLogin(): void
    {
        $this->client->request('GET', '/users');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateUserWithoutLogin(): void
    {
        $this->client->request('GET', '/users/create');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testEditUserWithoutLogin(): void
    {
        $this->client->request('GET', '/users/2/edit');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testViewUsersPagesWithSimpleUserLogin()
    {
        $this->userLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('GET', '/users/4/edit');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testViewUsersPagesWithAdminLogin()
    {
        $this->adminLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/users/4/edit');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateUserWithAdminLogin()
    {
        $this->adminLogin();

        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer un utilisateur');

        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

        $form = $crawler->selectButton('Ajouter')->form([
            'user' => [
                'username' => 'utilisateur_de_test',
                'password' => [
                    'first' => 'secret',
                    'second' => 'secret'
                ],
                'email' => 'test@example.com',
                'roles_options' => 'ROLE_USER'
            ]
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testEditUserWithAdminLogin()
    {
        $this->adminLogin();

        $crawler = $this->client->request('GET', '/users/4/edit');

        $this->assertSelectorTextContains('h1', 'Modifier simple_user_2_modify');

        $form = $crawler->selectButton('Modifier')->form([
            'user' => [
                'username' => 'simple_user_2_modified',
                'password' => [
                    'first' => 'secret',
                    'second' => 'secret'
                ],
                'email' => 'mail_modifie@example.com',
                'roles_options' => 'ROLE_USER'
            ]
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    /**
     * @dataProvider provideInvalidUser
     * @param array $formData
     * @param string $message
     */
    public function testEditUserWithAdminLoginFail(array $formData, string $message)
    {
        $this->adminLogin();

        $crawler = $this->client->request('GET', '/users/4/edit');

        $this->assertSelectorTextContains('h1', 'Modifier simple_user_2_modify');

        $form = $crawler->selectButton('Modifier')->form($formData);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Modifier');
        $this->assertSelectorTextContains('li', $message);
    }

    public function provideInvalidUser(): Generator
    {
        yield [
            [
                'user[username]' => '',
                'user[password][first]' => 'secret',
                'user[password][second]' => 'secret',
                'user[email]' => 'mail_modifie@example.com',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "Vous devez saisir un nom d'utilisateur."
        ];

        yield [
            [
                'user[username]' => 'simple_user_2_modify_too_long_for_the_25_length_required',
                'user[password][first]' => 'secret',
                'user[password][second]' => 'secret',
                'user[email]' => 'mail_modifie@example.com',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "Le nom d'utilisateur ne doit pas dépasser 25 caractères."
        ];

        yield [
            [
                'user[username]' => 'simple_user_2_modify',
                'user[password][first]' => 'secret',
                'user[password][second]' => 'secret',
                'user[email]' => '',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "Vous devez saisir une adresse email."
        ];

        yield [
            [
                'user[username]' => 'simple_user_2_modify',
                'user[password][first]' => 'secret',
                'user[password][second]' => 'secret',
                'user[email]' => 'fail',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "Le format de l'adresse n'est pas correct."
        ];

        yield [
            [
                'user[username]' => 'simple_user_2_modify',
                'user[password][first]' => 'secret',
                'user[password][second]' => 'secret',
                'user[email]' => 'email_too_long_for_the_60_remaining_caracters_allowed_for@email.field',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "L'email ne doit pas dépasser 60 caractères."
        ];

        yield [
            [
                'user[username]' => 'simple_user_2_modify',
                'user[password][first]' => '',
                'user[password][second]' => '',
                'user[email]' => 'mail_modifie@example.com',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "Vous devez saisir un mot de passe."
        ];

        yield [
            [
                'user[username]' => 'simple_user_2_modify',
                'user[password][first]' => 'password_too_long_for_the_64_remaining_caracters_allowed_for_password_field',
                'user[password][second]' => 'password_too_long_for_the_64_remaining_caracters_allowed_for_password_field',
                'user[email]' => 'mail_modifie@example.com',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "Le mot de passe ne doit pas dépasser 64 caractères."
        ];

        yield [
            [
                'user[username]' => 'simple_user_2_modify',
                'user[password][first]' => 'secret',
                'user[password][second]' => 'fail',
                'user[email]' => 'mail_modifie@example.com',
                'user[roles_options]' => 'ROLE_USER'
            ],
            "Les deux mots de passe doivent correspondre."
        ];
    }
}