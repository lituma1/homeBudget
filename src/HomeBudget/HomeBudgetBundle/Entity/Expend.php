<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Expend
 *
 * @ORM\Table(name="expend")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\ExpendRepository")
 */
class Expend
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
     * @Assert\Type(type="float")
     * @Assert\GreaterThan(value = 0, message="Kwota musi być dodatnia")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expendDate", type="date")
     */
    private $expendDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
    
    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="expends")
     */
    private $user;
    
    /**
     *@ORM\ManyToOne(targetEntity="Account", inversedBy="expends")
     * @Assert\NotNull(message="Proszę dodać jakieś konto, w zakładce konta")
     */
    private $account;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="ExpendCategory", inversedBy="expends")
     * @Assert\NotNull(message="Proszę zdefiniować kategorie wydatków, w zakładce wydatki")
     */
    private $expendCategory;
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
     * @return Expend
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
     * Set expendDate
     *
     * @param \DateTime $expendDate
     * @return Expend
     */
    public function setExpendDate($expendDate)
    {
        $this->expendDate = $expendDate;

        return $this;
    }

    /**
     * Get expendDate
     *
     * @return \DateTime 
     */
    public function getExpendDate()
    {
        return $this->expendDate;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Expend
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
     * @return Expend
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
     * Set expendCategory
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\expendCategory $expendCategory
     * @return Expend
     */
    public function setExpendCategory(\HomeBudget\HomeBudgetBundle\Entity\expendCategory $expendCategory = null)
    {
        $this->expendCategory = $expendCategory;

        return $this;
    }

    /**
     * Get expendCategory
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\expendCategory 
     */
    public function getExpendCategory()
    {
        return $this->expendCategory;
    }

    /**
     * Set account
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $account
     * @return Expend
     */
    public function setAccount(\HomeBudget\HomeBudgetBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \HomeBudget\HomeBudgetBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
}
