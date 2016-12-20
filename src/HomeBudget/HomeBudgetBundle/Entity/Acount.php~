<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acount
 *
 * @ORM\Table(name="acount")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\AcountRepository")
 */
class Acount
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
     * @ORM\Column(name="aim", type="string", length=255)
     */
    private $aim;

    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="acounts")
     */
    private $user;
    
    /**
     *@ORM\ManyToOne(targetEntity="Type", inversedBy="acounts")
     * @var type 
     */
    private $type;
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
     * @return Acount
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
     * @return Acount
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
     * @return Acount
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
     * @return Acount
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
}
