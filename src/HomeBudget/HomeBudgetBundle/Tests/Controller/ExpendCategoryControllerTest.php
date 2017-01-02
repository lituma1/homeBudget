<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpendCategoryControllerTest extends WebTestCase
{
    public function testNewexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expendCategory/new');
    }

    public function testModifyexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expendCategory/{id}/modify');
    }

    public function testShowallexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expendCategory/all');
    }
}
