<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExpendCategoryTest
 *
 * @author pp
 */
use HomeBudget\HomeBudgetBundle\Entity\ExpendCategory;
use HomeBudget\HomeBudgetBundle\Entity\User;
use HomeBudget\HomeBudgetBundle\Entity\Expend;

class ExpendCategoryTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() {
        parent::setUp();
        $this->testExCategory = new ExpendCategory();
        $this->testExpend = new Expend();
        $this->testUser = new User();
    }

    protected function tearDown() {
        $this->testExCategory = null;
        $this->testExpend = null;
        $this->testUser = null;
        parent::tearDown();
    }

    public function testGetId() {
        $this->assertEquals(null, $this->testExCategory->getId());
    }

    public function testGetName() {
        $this->assertEquals('', $this->testExCategory->getName());
    }

    public function testSetName() {
        $this->testExCategory->setName('jedzenie');
        $this->assertEquals('jedzenie', $this->testExCategory->getName());
    }

    public function testGetUser() {
        $this->assertEquals(null, $this->testExCategory->getUser());
    }

    public function testSetUser() {
       
        $this->testExCategory->setUser($this->testUser);
        $this->assertEquals($this->testUser, $this->testExCategory->getUser());
    }

    public function testGetExpends() {
        $this->assertCount(0, $this->testExCategory->getExpends());
    }

    public function testAddExpend() {
        
        $this->testExCategory->addExpend($this->testExpend);
        $this->assertCount(1, $this->testExCategory->getExpends());
        $this->assertContains($this->testExpend, $this->testExCategory->getExpends());
    }

    public function testRemoveExpend() {
       
        $this->testExCategory->addExpend($this->testExpend);
        $this->testExCategory->removeExpend($this->testExpend);
        $this->assertCount(0, $this->testExCategory->getExpends());
    }

    public function testGetStatus() {
        $this->assertEquals(null, $this->testExCategory->getStatus());
    }

    public function testSetStatus() {
        $this->testExCategory->setStatus(false);
        $this->assertFalse($this->testExCategory->getStatus());
        $this->testExCategory->setStatus(true);
        $this->assertTrue($this->testExCategory->getStatus());
    }

}
