<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new');
    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}/modify');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}/delete');
    }

    public function testShowall()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showAll');
    }

}
