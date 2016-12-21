<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpandCategoryControllerTest extends WebTestCase
{
    public function testNewexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expendCategory/new');
    }

    public function testModifyexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expandCategory/{id}/modify');
    }

    public function testDeleteexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expandCategory/{id}/delete');
    }

    public function testShowallexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expendCategory/all');
    }

}
