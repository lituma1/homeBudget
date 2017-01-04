<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpendControllerTest extends WebTestCase
{
    
    private $client;

    protected function setUp() {
        $this->client = static::createClient(array(), array(
                    'PHP_AUTH_USER' => 'Janek',
                    'PHP_AUTH_PW' => '123123',
        ));
    }
    
    public function testNewexpend()
    {
       $crawler = $this->client->request('GET', '/expend/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
       $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nowy wydatek")')->count()
        );
    }

    public function testModifyexpend()
    {
        $crawler = $this->client->request('GET', '/expend/21/modify', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));

        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź modyfikację wydatku")')->count()
        );
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
