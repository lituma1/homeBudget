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
use HomeBudget\HomeBudgetBundle\Entity\User;
use HomeBudget\HomeBudgetBundle\Entity\Type;


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
    public function testGetAim() {
        $this->assertEquals('', $this->testAccount->getAim());
    }
    public function testSetAim() {
        $this->testAccount->setAim('wakacje');
        $this->assertEquals('wakacje', $this->testAccount->getAim());
    }
    public function testGetUser() {
        $this->assertEquals(null, $this->testAccount->getUser());
    }
    public function testSetUser() {
        $user = new User();
        $this->testAccount->setUser($user);
        $this->assertEquals($user, $this->testAccount->getUser());
    }
    public function testGetType() {
        $this->assertEquals(null, $this->testAccount->getType());
    }
    public function testSetType() {
        $type = new Type();
        $this->testAccount->setType($type);
        $this->assertEquals($type, $this->testAccount->getType());
    }
    public function testGetName() {
        $this->assertEquals('', $this->testAccount->getName());
    }
    public function testSetName() {
        $this->testAccount->setName('Adam');
        $this->assertEquals('Adam', $this->testAccount->getName());
    }
}
