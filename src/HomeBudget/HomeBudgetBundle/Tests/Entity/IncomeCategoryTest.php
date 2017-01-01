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
        $this->testUser = new User();
        $this->testIncome = new Income();
    }

    protected function tearDown() {
        $this->testInCategory = null;
        $this->testUser = null;
        $this->testIncome = null;
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
        
        $this->testInCategory->addIncome($this->testIncome);
        $this->assertCount(1, $this->testInCategory->getIncomes());
        $this->assertContains($this->testIncome, $this->testInCategory->getIncomes());
    }

    public function testRemoveIncome() {
        
        $this->testInCategory->addIncome($this->testIncome);
        $this->testInCategory->removeIncome($this->testIncome);
        $this->assertCount(0, $this->testInCategory->getIncomes());
    }

    public function testGetUser() {
        $this->assertEquals(null, $this->testInCategory->getUser());
    }

    public function testSetUser() {
        
        $this->testInCategory->setUser($this->testUser);
        $this->assertEquals($this->testUser, $this->testInCategory->getUser());
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
