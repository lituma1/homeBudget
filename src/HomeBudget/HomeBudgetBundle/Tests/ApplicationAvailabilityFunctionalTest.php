<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplicationAvailabilityFunctionalTest
 *
 * @author pp
 */
namespace HBBundle\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ApplicationAvailabilityFunctionalTest extends WebTestCase{
    
    /**
     * @dataProvider urlProvider
     * 
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/'),
            array('/login'),
            array('/register/')
           
        );
    }
}
