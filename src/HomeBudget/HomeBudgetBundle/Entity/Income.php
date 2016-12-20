<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Income
 *
 * @ORM\Table(name="income")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\IncomeRepository")
 */
class Income
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
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="incomeDate", type="datetime")
     */
    private $incomeDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="incomes")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="incomeCategory", inversedBy="incomes")
     */
    private $incomeCategory;
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
     * Set amount
     *
     * @param string $amount
     * @return Income
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set incomeDate
     *
     * @param \DateTime $incomeDate
     * @return Income
     */
    public function setIncomeDate($incomeDate)
    {
        $this->incomeDate = $incomeDate;

        return $this;
    }

    /**
     * Get incomeDate
     *
     * @return \DateTime 
     */
    public function getIncomeDate()
    {
        return $this->incomeDate;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Income
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set user
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\User $user
     * @return Income
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
     * Set category
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\incomeCategory $category
     * @return Income
     */
    public function setCategory(\HomeBudget\HomeBudgetBundle\Entity\incomeCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\incomeCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set incomeCategory
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\incomeCategory $incomeCategory
     * @return Income
     */
    public function setIncomeCategory(\HomeBudget\HomeBudgetBundle\Entity\incomeCategory $incomeCategory = null)
    {
        $this->incomeCategory = $incomeCategory;

        return $this;
    }

    /**
     * Get incomeCategory
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\incomeCategory 
     */
    public function getIncomeCategory()
    {
        return $this->incomeCategory;
    }
}
