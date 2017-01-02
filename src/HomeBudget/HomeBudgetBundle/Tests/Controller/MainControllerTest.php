<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testShowmainpage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Panel');
    }

}
