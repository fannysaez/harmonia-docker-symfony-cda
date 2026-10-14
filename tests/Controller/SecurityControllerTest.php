<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class SecurityControllerTest extends AbstractWebTestCase
{
    public function testAccessOK(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_LOGIN);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    public function testFormExists(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_LOGIN);

        $this->assertSelectorExists('input[name="email"]');
        $this->assertSelectorExists('input[name="password"]');
        $this->assertSelectorExists('input[name="_remember_me"]');
        $this->assertSelectorExists('button[type="submit"]');
    }

    #[TestWith(['fanny.saez.0486@gmail.com', '#Fsaez?!du63', self::URL_HOME])]
    #[TestWith(['fanny.saez.0486@gmail.com', '', self::URL_LOGIN])]
    #[TestWith(['fanny.saez.0486@gmail.com', 'mauvais', self::URL_LOGIN])]
    #[TestWith(['inconnu@test.com', 'Password123', self::URL_LOGIN])]
    #[TestWith(['', '', self::URL_LOGIN])]
    public function testFormSubmit(
        string $email,
        string $password,
        string $path
    ): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_LOGIN);

        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = $email;
        $form['password'] = $password;
        $client->submit($form);

        $this->assertResponseRedirects($path);

        if ($path === self::URL_LOGIN) {
            $client->followRedirect();
            $this->assertSelectorExists('div.alert.alert-danger');
        }
    }

    public function testLogoutExistsAsLoggedUser(): void
    {
        $client = static::createClient();
        $this->loginUser($client);
        $client->request('GET', self::URL_HOME);

        $this->assertSelectorExists('a[href="/logout"]');
    }
}
