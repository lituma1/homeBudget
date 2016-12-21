<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IncomeCategoryControllerTest extends WebTestCase
{
    public function testNewinccategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/incomeCategory/new');
    }

    public function testModifyinccategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/incomeCategory/{id}/modify');
    }

    public function testShowallinccategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/incomeCategory/all');
    }

}
