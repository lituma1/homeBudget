<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase {

    private $client;

    protected function setUp() {
        $this->client = static::createClient(array(), array(
                    'PHP_AUTH_USER' => 'Janek',
                    'PHP_AUTH_PW' => '123123',
        ));
    }

    public function testNew() {



        $crawler = $this->client->request('GET', '/account/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Podaj dane dla nowego konta")')->count()
        );
    }

    public function testModify() {
        
        $crawler = $this->client->request('GET', '/account/{id}/modify', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
         $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nowe dane")')->count()
        );
    }
    
    public function testDelete() {
        

        $crawler = $this->client->request('GET', '/account/3/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("to the Expend:deleteExpend")')->count()
        );
    }

    public function testShowall() {


        $crawler = $this->client->request('GET', '/account/showAll', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Suma środków na Twoich kontach")')->count()
        );
    }

}
