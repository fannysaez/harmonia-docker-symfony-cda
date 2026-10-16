<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

class NavbarGenreTest extends AbstractWebTestCase
{
    public function testGenresDropdownIsAlphabetical(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL_HOME);

        $this->assertResponseIsSuccessful();

        $crawler = $client->getCrawler();

        $genreLinks = $crawler->filter('.navbar-nav .dropdown-menu .dropdown-item');

        $names = [];
        foreach ($genreLinks as $link) {
            $text = trim($link->textContent);
            if ($text !== 'Voir tous les genres') {
                $names[] = $text;
            }
        }

        $sorted = $names;
        sort($sorted);

        $this->assertSame($sorted, $names, 'Les genres ne sont pas triés par ordre alphabétique');
    }
}