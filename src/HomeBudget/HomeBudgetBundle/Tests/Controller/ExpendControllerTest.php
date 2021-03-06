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
    
    public function testNewExpend()
    {
       $crawler = $this->client->request('GET', '/expend/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
       $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nowy wydatek")')->count()
        );
    }

    public function testModifyExpend()
    {
        $crawler = $this->client->request('GET', '/expend/4/modify', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));

        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź modyfikację wydatku")')->count()
        );
    }

    public function testDeleteExpend()
    {
         $crawler = $this->client->request('GET', '/expend/4/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Potwierdź usunięcie poniższego wydatku")')->count()
        );
        
    }

    public function testAllExpend()
    {
        
        $crawler = $this->client->request('GET', '/expend/all', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Suma Twoich wydatków")')->count()
        );
    }

}
