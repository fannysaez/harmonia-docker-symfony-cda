<?php
namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testRegisterWithValidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->filter('form')->form([
            'registration_form[email]'         => 'test_' . uniqid() . '@example.com',
            'registration_form[pseudo]'        => 'TestUser' . uniqid(),
            'registration_form[plainPassword]' => 'Password123',
            'registration_form[agreeTerms]'    => true,
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testRegisterWithExistingEmail(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $existingEmail = $userRepository->findOneBy([])->getEmail();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->filter('form')->form([
            'registration_form[email]'         => $existingEmail,
            'registration_form[pseudo]'        => 'AutreUser',
            'registration_form[plainPassword]' => 'Password123',
            'registration_form[agreeTerms]'    => true,
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorExists('.is-invalid');
    }

    public function testRegisterWithPasswordTooShort(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->filter('form')->form([
            'registration_form[email]'         => 'shortpass@example.com',
            'registration_form[pseudo]'        => 'ShortPass',
            'registration_form[plainPassword]' => '123',
            'registration_form[agreeTerms]'    => true,
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorExists('.is-invalid');
    }

    public function testRegisterWithoutAgreeTerms(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->filter('form')->form([
            'registration_form[email]'         => 'noterms@example.com',
            'registration_form[pseudo]'        => 'NoTerms',
            'registration_form[plainPassword]' => 'Password123',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorExists('.is-invalid');
    }

    public function testRegisterWithEmptyFields(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->filter('form')->form([
            'registration_form[email]'         => '',
            'registration_form[pseudo]'        => '',
            'registration_form[plainPassword]' => '',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorExists('.is-invalid');
    }
}
