<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\AccountRepository")
 */
class Account
{
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
     */
    private $balance;
    
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
     *@ORM\ManyToOne(targetEntity="User", inversedBy="accounts")
     */
    private $user;
    
    /**
     *@ORM\ManyToOne(targetEntity="Type", inversedBy="accounts")
     * @var type 
     */
    private $type;
    
    /**
     * @ORM\OneToMany(targetEntity="Expend", mappedBy="account")
     */
   
    private $expendes;
    
    /**
     * @ORM\OneToMany(targetEntity="Income", mappedBy="account")
     */
   
    private $incomes;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set balance
     *
     * @param string $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set aim
     *
     * @param string $aim
     * @return Account
     */
    public function setAim($aim)
    {
        $this->aim = $aim;

        return $this;
    }

    /**
     * Get aim
     *
     * @return string 
     */
    public function getAim()
    {
        return $this->aim;
    }

    /**
     * Set user
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\User $user
     * @return Account
     */
    public function setUser(\HomeBudget\HomeBudgetBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Type $type
     * @return Account
     */
    public function setType(\HomeBudget\HomeBudgetBundle\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->expendes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add expendes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expendes
     * @return Account
     */
    public function addExpende(\HomeBudget\HomeBudgetBundle\Entity\Expend $expendes)
    {
        $this->expendes[] = $expendes;

        return $this;
    }

    /**
     * Remove expendes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expendes
     */
    public function removeExpende(\HomeBudget\HomeBudgetBundle\Entity\Expend $expendes)
    {
        $this->expendes->removeElement($expendes);
    }

    /**
     * Get expendes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpendes()
    {
        return $this->expendes;
    }

    /**
     * Add incomes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $incomes
     * @return Account
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
}
