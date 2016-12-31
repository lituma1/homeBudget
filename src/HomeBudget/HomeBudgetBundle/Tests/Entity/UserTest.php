<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserTest
 *
 * @author pp
 */
use HomeBudget\HomeBudgetBundle\Entity\User;
use HomeBudget\HomeBudgetBundle\Entity\Account;
use HomeBudget\HomeBudgetBundle\Entity\Expend;
use HomeBudget\HomeBudgetBundle\Entity\Income;
use HomeBudget\HomeBudgetBundle\Entity\Type;
use HomeBudget\HomeBudgetBundle\Entity\ExpendCategory;
use HomeBudget\HomeBudgetBundle\Entity\IncomeCategory;

class UserTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        parent::setUp();
        $this->testUser = new User();
        $this->testAccount = new Account();
        $this->testAccount2 = new Account();
        $this->testExpend = new Expend();
        $this->testIncome = new Income();
        $this->testType = new Type();
        $this->testExCategory = new ExpendCategory();
        $this->testInCategory = new IncomeCategory();
    }

    protected function tearDown() {
        $this->testUser = null;
        $this->testAccount = null;
        $this->testAccount2 = null;
        $this->testExpend = null;
        $this->testIncome = null;
        $this->testType = null;
        $this->testExCategory = null;
        $this->testInCategory = null;
        parent::tearDown();
    }

    public function testGetCellPhone() {
        $this->assertEquals(null, $this->testUser->getCellPhone());
    }

    public function testSetCellPhone() {
        $this->testUser->setCellPhone('123456789');
        $this->assertEquals('123456789', $this->testUser->getCellPhone());
    }

    public function testGetAccounts() {
        $this->assertCount(0, $this->testUser->getAccounts());
    }

    public function testAddAccount() {
        $this->testUser->addAccount($this->testAccount);
        $this->assertCount(1, $this->testUser->getAccounts());
        $this->assertContains($this->testAccount, $this->testUser->getAccounts());
    }

    public function testRemoveAccount() {
        $this->testUser->addAccount($this->testAccount);
        $this->testUser->removeAccount($this->testAccount);
        $this->assertCount(0, $this->testUser->getAccounts());
    }

    public function testBalanceOfAccounts() {
        $this->testAccount->setBalance(25);
        $this->testAccount2->setBalance(38);
        $this->testUser->addAccount($this->testAccount);
        $this->testUser->addAccount($this->testAccount2);
        $this->assertEquals(63, $this->testUser->balanceOfAccounts());
    }

    public function testGetExpends() {
        $this->assertCount(0, $this->testUser->getExpends());
    }

    public function testAddExpend() {
        $this->testUser->addExpend($this->testExpend);
        $this->assertCount(1, $this->testUser->getExpends());
        $this->assertContains($this->testExpend, $this->testUser->getExpends());
    }

    public function testRemoveExpend() {
        $this->testUser->addExpend($this->testExpend);
        $this->testUser->removeExpend($this->testExpend);
        $this->assertCount(0, $this->testUser->getExpends());
    }

    public function testGetIncomes() {
        $this->assertCount(0, $this->testUser->getIncomes());
    }

    public function testAddIncome() {
        $this->testUser->addIncome($this->testIncome);
        $this->assertCount(1, $this->testUser->getIncomes());
        $this->assertContains($this->testIncome, $this->testUser->getIncomes());
    }

    public function testRemoveIncome() {
        $this->testUser->addIncome($this->testIncome);
        $this->testUser->removeIncome($this->testIncome);
        $this->assertCount(0, $this->testUser->getIncomes());
    }

    public function testGetTypes() {
        $this->assertCount(0, $this->testUser->getTypes());
    }

    public function testAddType() {
        $this->testUser->addType($this->testType);
        $this->assertCount(1, $this->testUser->getTypes());
        $this->assertContains($this->testType, $this->testUser->getTypes());
    }

    public function testRemoveType() {
        $this->testUser->addType($this->testType);
        $this->testUser->removeType($this->testType);
        $this->assertCount(0, $this->testUser->getTypes());
    }
    
    public function testGetExpendCategories() {
        $this->assertCount(0, $this->testUser->getExpendCategories());
    }

    public function testAddExpendCategory() {
        $this->testUser->addExpendCategory($this->testExCategory);
        $this->assertCount(1, $this->testUser->getExpendCategories());
        $this->assertContains($this->testExCategory, $this->testUser->getExpendCategories());
    }

    public function testRemoveExpendCategory() {
        $this->testUser->addExpendCategory($this->testExCategory);
        $this->testUser->removeExpendCategory($this->testExCategory);
        $this->assertCount(0, $this->testUser->getExpendCategories());
    }
    
    public function testGetIncomeCategories() {
        $this->assertCount(0, $this->testUser->getIncomeCategories());
    }

    public function testAddIncomeCategory() {
        $this->testUser->addIncomeCategory($this->testInCategory);
        $this->assertCount(1, $this->testUser->getIncomeCategories());
        $this->assertContains($this->testInCategory, $this->testUser->getIncomeCategories());
    }

    public function testRemoveIncomeCategory() {
        $this->testUser->addIncomeCategory($this->testInCategory);
        $this->testUser->removeIncomeCategory($this->testInCategory);
        $this->assertCount(0, $this->testUser->getIncomeCategories());
    }
}
