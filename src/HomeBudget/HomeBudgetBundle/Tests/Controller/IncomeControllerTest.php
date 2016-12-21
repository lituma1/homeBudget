<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IncomeControllerTest extends WebTestCase
{
    public function testNewincome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/income/new');
    }

    public function testModifyincome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/income/{id}/modify');
    }

    public function testDeleteincome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/income/{id}/delete');
    }

    public function testAllincome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/income/all');
    }

}
