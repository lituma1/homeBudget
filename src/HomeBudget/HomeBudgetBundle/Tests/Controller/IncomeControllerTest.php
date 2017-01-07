<?php

namespace HomeBudget\HomeBudgetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IncomeControllerTest extends WebTestCase
{
    private $client;

    protected function setUp() {
        $this->client = static::createClient(array(), array(
                    'PHP_AUTH_USER' => 'Janek',
                    'PHP_AUTH_PW' => '123123',
        ));
    }
    public function testNewincome()
    {
         $crawler = $this->client->request('GET', '/income/new', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));
       $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź nowy przychód")')->count()
        );
    }

    public function testModifyincome()
    {
        $crawler = $this->client->request('GET', '/income/5/modify', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));

        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Wprowadź modyfikację przychodu")')->count()
        );
    }

    public function testDeleteincome()
    {
       $crawler = $this->client->request('GET', '/income/5/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));

        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Potwierdź usunięcie poniższego przychodu")')->count()
        );
    }

    public function testAllincome()
    {
        $crawler = $this->client->request('GET', '/income/all', array(), array(), array(
            'PHP_AUTH_USER' => 'Janek',
            'PHP_AUTH_PW' => '123123',
        ));

        $this->assertGreaterThan(
                0, $crawler->filter('html:contains("Twoje przychody")')->count()
        );
    }

}
