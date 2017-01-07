<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TypeControllerTest extends WebTestCase
{
    
    private $client;

    protected function setUp() {
        $this->client = static::createClient(array(), array(
                    'PHP_AUTH_USER' => 'Janek',
                    'PHP_AUTH_PW' => '123123',
        ));
    }
    public function testNew()
    {
         $crawler = $this->client->request('GET', '/type/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
       $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nowy typ konta")')->count()
        );
       $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Lista Twoich kategorii")')->count()
        );
    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/type/{id}/modify');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/type/{id}/delete');
    }

    public function testShowall()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/type/showAll');
    }

}
