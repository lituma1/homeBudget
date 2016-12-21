<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpendControllerTest extends WebTestCase
{
    public function testNewexpend()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expend/new');
    }

    public function testModifyexpend()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expend/{id}/modify');
    }

    public function testDeleteexpend()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expend/{id}/delete');
    }

    public function testAllexpend()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expend/all');
    }

}
