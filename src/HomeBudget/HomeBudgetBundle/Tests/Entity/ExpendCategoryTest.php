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
    }

    protected function tearDown() {
        $this->testExCategory = null;
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
        $user = new User();
        $this->testExCategory->setUser($user);
        $this->assertEquals($user, $this->testExCategory->getUser());
    }

    public function testGetExpends() {
        $this->assertCount(0, $this->testExCategory->getExpends());
    }

    public function testAddExpend() {
        $expend = new Expend();
        $this->testExCategory->addExpend($expend);
        $this->assertCount(1, $this->testExCategory->getExpends());
        $this->assertContains($expend, $this->testExCategory->getExpends());
    }

    public function testRemoveExpend() {
        $expend = new Expend();
        $this->testExCategory->addExpend($expend);
        $this->testExCategory->removeExpend($expend);
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
