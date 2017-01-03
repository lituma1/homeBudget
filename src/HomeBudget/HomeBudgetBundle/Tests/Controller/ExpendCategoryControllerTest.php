<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpendCategoryControllerTest extends WebTestCase
{
    
    private $client;

    protected function setUp() {
        $this->client = static::createClient(array(), array(
                    'PHP_AUTH_USER' => 'Janek',
                    'PHP_AUTH_PW' => '123123',
        ));
    }
    
    public function testNewexpcategory()
    {
        $crawler = $this->client->request('GET', '/expendCategory/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nową kategorię dla wydatku")')->count()
        );
    }

    public function testModifyexpcategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expendCategory/{id}/modify');
    }

}
