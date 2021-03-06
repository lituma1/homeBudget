<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\AccountRepository")
 */
class Account {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="balance", type="decimal", precision=10, scale=2)
     * @Assert\GreaterThanOrEqual(value = 0, message="Liczba musi być dodatnia")
     * 
     */
    private $balance;
    
    /**
     *
     * @var type 
     * @ORM\Column(name="status", type="boolean")
     * 
     */
    private $status;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="aim", type="string", length=255)
     */
    private $aim;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="accounts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="accounts")
     * @var type 
     * @Assert\NotNull(message="Proszę zdefiniować typy kont, w zakładce konta")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="Expend", mappedBy="account")
     */
    private $expends;

    /**
     * @ORM\OneToMany(targetEntity="Income", mappedBy="account")
     */
    private $incomes;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set balance
     *
     * @param string $balance
     * @return Account
     */
    public function setBalance($balance) {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string 
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * Set aim
     *
     * @param string $aim
     * @return Account
     */
    public function setAim($aim) {
        $this->aim = $aim;

        return $this;
    }

    /**
     * Get aim
     *
     * @return string 
     */
    public function getAim() {
        return $this->aim;
    }

    /**
     * Set user
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\User $user
     * @return Account
     */
    public function setUser(\HomeBudget\HomeBudgetBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Type $type
     * @return Account
     */
    public function setType(\HomeBudget\HomeBudgetBundle\Entity\Type $type = null) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\Type 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Account
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->expends = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incomes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add incomes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $incomes
     * @return Account
     */
    public function addIncome(\HomeBudget\HomeBudgetBundle\Entity\Income $incomes) {
        $this->incomes[] = $incomes;

        return $this;
    }

    /**
     * Remove incomes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $incomes
     */
    public function removeIncome(\HomeBudget\HomeBudgetBundle\Entity\Income $incomes) {
        $this->incomes->removeElement($incomes);
    }

    /**
     * Get incomes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncomes() {
        return $this->incomes;
    }

    /**
     * Add amount of money to account
     * 
     * @param float
     * @return Account
     */
    public function addMoney($amount) {

        $balance = $this->getBalance();

        $balance += $amount;
        $this->setBalance($balance);
        return $this;
    }
    
    /**
     * Spend amount of money from account
     * 
     * @param type $amount
     * @return boolean
     */
    public function spendMoney($amount) {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            return true;
        }
        return false;
    }


    /**
     * Set status
     *
     * @param boolean $status
     * @return Account
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add expends
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expends
     * @return Account
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
     * Create string with name and balance of account
     * 
     * @return string
     */
    public function __toString() {
        return $this->getName().' aktualne saldo: '.$this->getBalance();
    }
}
