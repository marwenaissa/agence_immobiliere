<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProprietaireControllerTest extends WebTestCase{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proprietaire');

        self::assertResponseIsSuccessful();
    }
}
