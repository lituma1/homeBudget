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
}
