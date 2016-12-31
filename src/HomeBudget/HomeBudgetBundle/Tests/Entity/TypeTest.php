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
    }

    protected function tearDown() {
        $this->testType = null;
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
        $account = new Account();
        $this->testType->addAccount($account);
        $this->assertCount(1, $this->testType->getAccounts());
        $this->assertContains($account, $this->testType->getAccounts());
    }

    public function testRemoveAccount() {
        $account = new Account();
        $this->testType->addAccount($account);
        $this->testType->removeAccount($account);
        $this->assertCount(0, $this->testType->getAccounts());
        
    }

    public function testGetUser() {
        $this->assertEquals(null, $this->testType->getUser());
    }

    public function testSetUser() {
        $user = new User();
        $this->testType->setUser($user);
        $this->assertEquals($user, $this->testType->getUser($user));
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
