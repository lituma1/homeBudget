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
    
    public function testNewinccategory()
    {
        $crawler = $this->client->request('GET', '/incomeCategory/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nową kategorię dla przychodów")')->count()
        );
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
