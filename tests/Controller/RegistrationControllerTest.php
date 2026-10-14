<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class RegistrationControllerTest extends AbstractWebTestCase
{
    public function testRegisterPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_REGISTER);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testFormExists(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_REGISTER);

        $this->assertSelectorExists('input[name="registration_form[email]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword]"]');
        $this->assertSelectorExists('input[name="registration_form[pseudo]"]');
        $this->assertSelectorExists('input[name="registration_form[agreeTerms]"]');
        $this->assertSelectorTextContains('button[type="submit"]', "S'inscrire");
    }

    #[TestWith(['pas-un-email', 'TestUser', 'Password123', true, 422])]
    #[TestWith(['test@test.com', 'TestUser', '123', true, 422])]
    #[TestWith(['fanny.saez.0486@gmail.com', 'TestUser', 'Password123', true, 422])]
    #[TestWith(['test@test.com', 'TestUser', 'Password123', false, 422])]
    public function testRegisterWithInvalidData(
        string $email,
        string $pseudo,
        string $password,
        bool $agreeTerms,
        int $code
    ): void {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_REGISTER);
        $form = $crawler->filter('form')->form([
            'registration_form[email]'         => $email,
            'registration_form[pseudo]'        => $pseudo,
            'registration_form[plainPassword]' => $password,
            'registration_form[agreeTerms]'    => $agreeTerms,
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame($code);
        $this->assertSelectorExists('.is-invalid');
    }

    public function testRegisterWithValidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URL_REGISTER);
        $form = $crawler->filter('form')->form([
            'registration_form[email]'         => 'test_' . uniqid() . '@example.com',
            'registration_form[pseudo]'        => 'TestUser' . uniqid(),
            'registration_form[plainPassword]' => 'Password123',
            'registration_form[agreeTerms]'    => true,
        ]);
        $client->submit($form);
        $this->assertResponseRedirects(self::URL_HOME);
    }
}
