<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TypeTest
 *
 * @author pp
 */
use HomeBudget\HomeBudgetBundle\Entity\Type;
use HomeBudget\HomeBudgetBundle\Entity\Account;
use HomeBudget\HomeBudgetBundle\Entity\User;

class TypeTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        parent::setUp();
        $this->testType = new Type();
        $this->testAccount = new Account();
        $this->testUser = new User();
    }

    protected function tearDown() {
        $this->testType = null;
        $this->testAccount = null;
        $this->testUser = null;
        parent::tearDown();
    }

    public function testGetId() {
        $this->assertEquals(null, $this->testType->getId());
    }

    public function testGetName() {
        $this->assertEquals('', $this->testType->getName());
    }

    public function testSetName() {
        $this->testType->setName('ROR');
        $this->assertEquals('ROR', $this->testType->getName());
    }

    public function testGetAccounts() {
        $this->assertCount(0, $this->testType->getAccounts());
    }

    public function testAddAccount() {
        
        $this->testType->addAccount($this->testAccount);
        $this->assertCount(1, $this->testType->getAccounts());
        $this->assertContains($this->testAccount, $this->testType->getAccounts());
    }

    public function testRemoveAccount() {
        
        $this->testType->addAccount($this->testAccount);
        $this->testType->removeAccount($this->testAccount);
        $this->assertCount(0, $this->testType->getAccounts());
        
    }

    public function testGetUser() {
        $this->assertEquals(null, $this->testType->getUser());
    }

    public function testSetUser() {
       
        $this->testType->setUser($this->testUser);
        $this->assertEquals($this->testUser, $this->testType->getUser($this->testUser));
    }
    
    public function testGetStatus() {
        $this->assertEquals(null, $this->testType->getStatus());
    }
    
    public function testSetStatus() {
        $this->testType->setStatus(false);
        $this->assertFalse($this->testType->getStatus());
        $this->testType->setStatus(true);
        $this->assertTrue($this->testType->getStatus());
    }
}
