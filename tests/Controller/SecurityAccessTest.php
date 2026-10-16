<?php
namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class SecurityAccessTest extends AbstractWebTestCase
{
    // ---- tests originaux ----

    public function testProfilRedirectsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profil');
        $this->assertResponseRedirects('/login');
    }

    public function testFavoritesRedirectsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favorites');
        $this->assertResponseRedirects('/login');
    }

    public function testAdminRedirectsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseRedirects('/login');
    }

    public function testAdminRedirectsIfNotAdmin(): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'lourdes.sawayn@example.net');
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403);
    }

    // ---- dataProvider routes protégées non connecté ----

    public static function protectedRoutesProvider(): array
    {
        return [
            'profil'          => ['/profil'],
            'favorites'       => ['/favorites'],
            'admin dashboard' => ['/admin'],
            'admin albums'    => ['/admin/albums'],
            'admin artists'   => ['/admin/artists'],
            'admin genres'    => ['/admin/genres'],
            'admin users'     => ['/admin/users'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('protectedRoutesProvider')]
    public function testProtectedRouteRedirectsToLogin(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertResponseRedirects('/login');
    }

    // ---- dataProvider routes admin → 403 si user normal ----

    public static function adminRoutesProvider(): array
    {
        return [
            'admin dashboard' => ['/admin'],
            'admin albums'    => ['/admin/albums'],
            'admin artists'   => ['/admin/artists'],
            'admin genres'    => ['/admin/genres'],
            'admin users'     => ['/admin/users'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('adminRoutesProvider')]
    public function testAdminRouteForbiddenForRegularUser(string $url): void
    {
        $client = static::createClient();
        $this->loginUser($client, 'lourdes.sawayn@example.net');
        $client->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }
}