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
        $this->testAccount->setName('PKO BP');
        $this->assertEquals('PKO BP', $this->testAccount->getName());
    }

    public function testGetIncomes() {
        $this->assertCount(0, $this->testAccount->getIncomes());
    }

    public function testAddIncome() {
        $income = new Income();
        $this->testAccount->addIncome($income);
        $this->assertCount(1, $this->testAccount->getIncomes());
        $this->assertContains($income, $this->testAccount->getIncomes());
    }

    public function testRemoveIncome() {
        $income = new Income();
        $this->testAccount->addIncome($income);
        $this->testAccount->removeIncome($income);
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
        $expend = new Expend();
        $this->testAccount->addExpend($expend);
        $this->assertCount(1, $this->testAccount->getExpends());
        $this->assertContains($expend, $this->testAccount->getExpends());
    }

    public function testRemoveExpend() {
        $epend = new Expend();
        $this->testAccount->addExpend($epend);
        $this->testAccount->removeExpend($epend);
        $this->assertCount(0, $this->testAccount->getExpends());
    }
    public function testToString() {
        $this->testAccount->setBalance(100);
        $this->testAccount->setName('ING');
        $this->assertEquals('ING aktualne saldo: 100', $this->testAccount->__toString());
    }
}
