<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class FormTest extends AbstractWebTestCase
{
    public function testLoginFormExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertCount(1, $crawler->filter('input[name="email"]'));
        $this->assertCount(1, $crawler->filter('input[name="password"]'));
    }

    public function testLoginFormSubmitWithWrongCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'mauvais@email.com',
            'password' => 'mauvaismdp',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/login');
    }

    public function testRegisterFormExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertCount(1, $crawler->filter('input[name="registration_form[email]"]'));
        $this->assertCount(1, $crawler->filter('input[name="registration_form[pseudo]"]'));
    }
}