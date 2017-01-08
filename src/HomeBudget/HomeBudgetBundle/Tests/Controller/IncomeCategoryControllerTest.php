<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IncomeCategoryControllerTest extends WebTestCase
{
    
    private $client;

    protected function setUp() {
        $this->client = static::createClient(array(), array(
                    'PHP_AUTH_USER' => 'Janek',
                    'PHP_AUTH_PW' => '123123',
        ));
    }
    
    public function testNewIncCategory()
    {
        $crawler = $this->client->request('GET', '/incomeCategory/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nową kategorię dla przychodów")')->count()
        );
         $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Lista Twoich kategorii")')->count()
        );
    }
    

    public function testModifyIncCategory()
    {
        $crawler = $this->client->request('GET', '/incomeCategory/7/modify', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź zmiany dla kategorii")')->count()
        );

    }

}
