<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExpandTest
 *
 * @author pp
 */
use HomeBudget\HomeBudgetBundle\Entity\Expend;
use HomeBudget\HomeBudgetBundle\Entity\User;
use HomeBudget\HomeBudgetBundle\Entity\ExpendCategory;
use HomeBudget\HomeBudgetBundle\Entity\Account;

class ExpendTest extends \PHPUnit\Framework\TestCase{
   
    protected function setUp() {
        parent::setUp();
        $this->testExpend = new Expend();
        $this->testUser = new User();
        $this->testExCategory = new ExpendCategory();
        $this->testAccount = new Account();
        
    }
    protected function tearDown() {
        $this->testExpend = null;
        $this->testUser = null;
        $this->testExCategory = null;
        $this->testAccount = null;
        parent::tearDown();
    }
    
    public function testGetId() {
        $this->assertEquals(null, $this->testExpend->getId());
    }
    public function testGetAmount() {
        $this->assertEquals(null, $this->testExpend->getAmount());
    }
    public function testSetAmount() {
        $this->testExpend->setAmount(4.5);
        $this->assertEquals(4.5, $this->testExpend->getAmount());
    }
    public function testGetExpendDate() {
        $this->assertEquals(null, $this->testExpend->getExpendDate());
    }
    public function testSetExpendDate() {
        $this->testExpend->setExpendDate('23/12/2016');
        $this->assertEquals('23/12/2016', $this->testExpend->getExpendDate());
    }
    public function testGetDescription() {
        $this->assertEquals(null, $this->testExpend->getDescription());
    }
    public function testSetDescription() {
        $this->testExpend->setDescription('owoce na rynku');
        $this->assertEquals('owoce na rynku', $this->testExpend->getDescription());
    }
    public function testGetUser() {
        $this->assertEquals(null, $this->testExpend->getUser());
    }
    public function testSetUser() {
        $this->testExpend->setUser($this->testUser);
        $this->assertEquals($this->testUser, $this->testExpend->getUser());
    }
    public function testGetExpendCategory() {
        $this->assertEquals(null, $this->testExpend->getExpendCategory());
    }
    public function testSetExpendCategory() {
        $this->testExpend->setExpendCategory($this->testExCategory);
        $this->assertEquals($this->testExCategory, $this->testExpend->getExpendCategory());
    }
    public function testGetAccount() {
        $this->assertEquals(null, $this->testExpend->getAccount());
    }
    public function testSetAccount() {
        $this->testExpend->setAccount($this->testAccount);
        $this->assertEquals($this->testAccount, $this->testExpend->getAccount());
    }
}
