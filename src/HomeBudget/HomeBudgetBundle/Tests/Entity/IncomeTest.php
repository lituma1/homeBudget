<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IncomeTest
 *
 * @author pp
 */
use HomeBudget\HomeBudgetBundle\Entity\Income;
use HomeBudget\HomeBudgetBundle\Entity\User;
use HomeBudget\HomeBudgetBundle\Entity\IncomeCategory;
use HomeBudget\HomeBudgetBundle\Entity\Account;

class IncomeTest extends \PHPUnit\Framework\TestCase{
    
    protected function setUp() {
        parent::setUp();
        $this->testIncome = new Income();
        $this->testUser = new User();
        $this->testInCategory = new IncomeCategory();
        $this->testAccount = new Account();
        
    }
    protected function tearDown() {
        $this->testIncome = null;
        $this->testUser = null;
        $this->testInCategory = null;
        $this->testAccount = null;
        parent::tearDown();
    }
    
    public function testGetId() {
        $this->assertEquals(null, $this->testIncome->getId());
    }
    
    public function testGetAmount() {
        $this->assertEquals(null, $this->testIncome->getAmount());
    }
    public function testSetAmount() {
        $this->testIncome->setAmount(56.3);
        $this->assertEquals(56.3, $this->testIncome->getAmount());
    }
    public function testGetIncomeDate() {
        $this->assertEquals(null, $this->testIncome->getIncomeDate());
    }
    public function testSetIncomeDate() {
        $this->testIncome->setIncomeDate('28/12/2016');
        $this->assertEquals('28/12/2016', $this->testIncome->getIncomeDate());
    }
    public function testGetDescription() {
        $this->assertEquals(null, $this->testIncome->getDescription());
    }
    public function testSetDescription() {
        $this->testIncome->setDescription('owoce na rynku');
        $this->assertEquals('owoce na rynku', $this->testIncome->getDescription());
    }
    public function testGetUser() {
        $this->assertEquals(null, $this->testIncome->getUser());
    }
    public function testSetUser() {
        $this->testIncome->setUser($this->testUser);
        $this->assertEquals($this->testUser, $this->testIncome->getUser());
    }
    public function testGetIncomeCategory() {
        $this->assertEquals(null, $this->testIncome->getIncomeCategory());
    }
    public function testSetIncomeCategory() {
        $this->testIncome->setIncomeCategory($this->testInCategory);
        $this->assertEquals($this->testInCategory, $this->testIncome->getIncomeCategory());
    }
    public function testGetAccount() {
        $this->assertEquals(null, $this->testIncome->getAccount());
    }
    public function testSetAccount() {
        $this->testIncome->setAccount($this->testAccount);
        $this->assertEquals($this->testAccount, $this->testIncome->getAccount());
    }
}
