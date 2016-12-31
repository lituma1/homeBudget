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
use HomeBudget\HomeBudgetBundle\Entity\Income;
use HomeBudget\HomeBudgetBundle\Entity\Expend;

class AccountTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        parent::setUp();
        $this->testAccount = new Account();
        $this->testUser = new User();
        $this->testType = new Type();
        $this->testIncome = new Income();
        $this->testExpend = new Expend();
    }

    protected function tearDown() {
        $this->testAccount = null;
        $this->testUser = null;
        $this->testType = null;
        $this->testIncome = null;
        $this->testExpend = null;

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

        $this->testAccount->setUser($this->testUser);
        $this->assertEquals($this->testUser, $this->testAccount->getUser());
    }

    public function testGetType() {
        $this->assertEquals(null, $this->testAccount->getType());
    }

    public function testSetType() {
        $this->testAccount->setType($this->testType);
        $this->assertEquals($this->testType, $this->testAccount->getType());
    }

    public function testGetName() {
        $this->assertEquals('', $this->testAccount->getName());
    }

    public function testSetName() {
        $this->testAccount->setName('PKO BP');
        $this->assertEquals('PKO BP', $this->testAccount->getName());
    }

    public function testGetIncomes() {
        $this->assertCount(0, $this->testAccount->getIncomes());
    }

    public function testAddIncome() {
        
        $this->testAccount->addIncome($this->testIncome);
        $this->assertCount(1, $this->testAccount->getIncomes());
        $this->assertContains($this->testIncome, $this->testAccount->getIncomes());
    }

    public function testRemoveIncome() {
       
        $this->testAccount->addIncome($this->testIncome);
        $this->testAccount->removeIncome($this->testIncome);
        $this->assertCount(0, $this->testAccount->getIncomes());
    }

    public function testAddMoney() {
        $this->testAccount->addMoney(300);
        $this->assertEquals(300, $this->testAccount->getBalance());
    }

    public function testSpendMoney() {
        $this->assertFalse($this->testAccount->spendMoney(3));
        $this->testAccount->addMoney(100);
        $this->testAccount->spendMoney(30);
        $this->assertEquals(70, $this->testAccount->getBalance());
    }

    public function testGetStatus() {
        $this->assertEquals(null, $this->testAccount->getStatus());
    }

    public function testSetStatus() {
        $this->testAccount->setStatus(false);
        $this->assertFalse($this->testAccount->getStatus());
        $this->testAccount->setStatus(true);
        $this->assertTrue($this->testAccount->getStatus());
    }

    public function testGetExpends() {
        $this->assertCount(0, $this->testAccount->getExpends());
    }

    public function testAddExpend() {
        
        $this->testAccount->addExpend($this->testExpend);
        $this->assertCount(1, $this->testAccount->getExpends());
        $this->assertContains($this->testExpend, $this->testAccount->getExpends());
    }

    public function testRemoveExpend() {
        
        $this->testAccount->addExpend($this->testExpend);
        $this->testAccount->removeExpend($this->testExpend);
        $this->assertCount(0, $this->testAccount->getExpends());
    }

    public function testToString() {
        $this->testAccount->setBalance(100);
        $this->testAccount->setName('ING');
        $this->assertEquals('ING aktualne saldo: 100', $this->testAccount->__toString());
    }

}
