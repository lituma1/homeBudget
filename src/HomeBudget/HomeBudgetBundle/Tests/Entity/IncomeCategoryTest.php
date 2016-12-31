<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IncomeCategoryTest
 *
 * @author pp
 */
use HomeBudget\HomeBudgetBundle\Entity\IncomeCategory;
use HomeBudget\HomeBudgetBundle\Entity\Income;
use HomeBudget\HomeBudgetBundle\Entity\User;

class IncomeCategoryTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        parent::setUp();
        $this->testInCategory = new IncomeCategory();
    }

    protected function tearDown() {
        $this->testInCategory = null;
        parent::tearDown();
    }

    public function testGetId() {
        $this->assertEquals(null, $this->testInCategory->getId());
    }

    public function testGetName() {
        $this->assertEquals('', $this->testInCategory->getName());
    }

    public function testSetName() {
        $this->testInCategory->setName('pensja');
        $this->assertEquals('pensja', $this->testInCategory->getName());
    }

    public function testGetIncomes() {
        $this->assertCount(0, $this->testInCategory->getIncomes());
    }

    public function testAddIncome() {
        $income = new Income();
        $this->testInCategory->addIncome($income);
        $this->assertCount(1, $this->testInCategory->getIncomes());
        $this->assertContains($income, $this->testInCategory->getIncomes());
    }

    public function testRemoveIncome() {
        $income = new Income();
        $this->testInCategory->addIncome($income);
        $this->testInCategory->removeIncome($income);
        $this->assertCount(0, $this->testInCategory->getIncomes());
    }

    public function testGetUser() {
        $this->assertEquals(null, $this->testInCategory->getUser());
    }

    public function testSetUser() {
        $user = new User();
        $this->testInCategory->setUser($user);
        $this->assertEquals($user, $this->testInCategory->getUser());
    }

    public function testGetStatus() {
        $this->assertEquals(null, $this->testInCategory->getStatus());
    }

    public function testSetStatus() {
        $this->testInCategory->setStatus(false);
        $this->assertFalse($this->testInCategory->getStatus());
        $this->testInCategory->setStatus(true);
        $this->assertTrue($this->testInCategory->getStatus());
    }

}
