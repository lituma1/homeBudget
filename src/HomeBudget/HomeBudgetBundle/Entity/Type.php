<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table(name="type")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\TypeRepository")
 */
class Type
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     *@ORM\OneToMany(targetEntity="Account", mappedBy="type")
     * @var type 
     */
    private $accounts;
    
    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="type")
     * @var type 
     */
    private $user;
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
     * @return Type
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
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add accounts
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $accounts
     * @return Type
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
        return $this->acounts;
    }

    /**
     * Add accounts
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Account $accounts
     * @return Type
     */
    

    

    

    /**
     * Set user
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\User $user
     * @return Type
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
}
