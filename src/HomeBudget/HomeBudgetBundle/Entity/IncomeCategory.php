<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IncomeCategory
 *
 * @ORM\Table(name="income_category")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\IncomeCategoryRepository")
 */
class IncomeCategory
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    
    /**
     * @ORM\OneToMany(targetEntity="Income", mappedBy="IncomeCategory")
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
     * Set name
     *
     * @param string $name
     * @return IncomeCategory
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
        $this->incomes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add incomes
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Income $incomes
     * @return IncomeCategory
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
