<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author pp
 */
namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="my_users")
 */
class User extends BaseUser {
    
    /**
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     * 
     */
    private $cellPhone;
    
    /**
     *@ORM\OneToMany(targetEntity="Account", mappedBy="user")
     * @var type 
     */
    private $accounts;
    
    /**
     * @ORM\OneToMany(targetEntity="Expend", mappedBy="user")
     */
    private $expends;
    
    /**
     * @ORM\OneToMany(targetEntity="Income", mappedBy="user")
     */
    private $incomes;
    
    /**
     * @ORM\OneToMany(targetEntity="Type", mappedBy="user")
     */
    private $types;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getCellPhone() {
        return $this->cellPhone;
    }
    public function setCellPhone($cellPhone) {
        $this->cellPhone = $cellPhone;
    }




    /**
     * Add accounts
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $accounts
     * @return User
     */
    public function addAccount(\HomeBudget\HomeBudgetBundle\Entity\Account $accounts)
    {
        $this->accounts[] = $accounts;

        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $accounts
     */
    public function removeAccount(\HomeBudget\HomeBudgetBundle\Entity\Account $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Add expends
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expends
     * @return User
     */
    public function addExpend(\HomeBudget\HomeBudgetBundle\Entity\Expend $expends)
    {
        $this->expends[] = $expends;

        return $this;
    }

    /**
     * Remove expends
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expends
     */
    public function removeExpend(\HomeBudget\HomeBudgetBundle\Entity\Expend $expends)
    {
        $this->expends->removeElement($expends);
    }

    /**
     * Get expends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpends()
    {
        return $this->expends;
    }

    /**
     * Add incomes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $incomes
     * @return User
     */
    public function addIncome(\HomeBudget\HomeBudgetBundle\Entity\Income $incomes)
    {
        $this->incomes[] = $incomes;

        return $this;
    }

    /**
     * Remove incomes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $incomes
     */
    public function removeIncome(\HomeBudget\HomeBudgetBundle\Entity\Income $incomes)
    {
        $this->incomes->removeElement($incomes);
    }

    /**
     * Get incomes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncomes()
    {
        return $this->incomes;
    }

    /**
     * Add types
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Type $types
     * @return User
     */
    public function addType(\HomeBudget\HomeBudgetBundle\Entity\Type $types)
    {
        $this->types[] = $types;

        return $this;
    }

    /**
     * Remove types
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Type $types
     */
    public function removeType(\HomeBudget\HomeBudgetBundle\Entity\Type $types)
    {
        $this->types->removeElement($types);
    }

    /**
     * Get types
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypes()
    {
        return $this->types;
    }
}
