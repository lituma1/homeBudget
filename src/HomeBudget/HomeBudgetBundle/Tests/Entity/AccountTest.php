<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccoutTest
 *
 * @author pp
 */
use HomeBudget\HomeBudgetBundle\Entity\Account;

class AccountTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        parent::setUp();
        $this->testAccount = new Account();
        
    }
     protected function tearDown() {
        $this->testAccount = null;
        parent::tearDown();
    }
    

    public function testGetId() {
        $this->assertEquals('', $this->testAccount->getId());
    }
    
    public function testGetBalance() {
        $this->assertEquals(0, $this->testAccount->getBalance());
    }
    public function testSetBalance() {
        $this->testAccount->setBalance(400);
        $this->assertEquals(400, $this->testAccount->getBalance());
    }
}
