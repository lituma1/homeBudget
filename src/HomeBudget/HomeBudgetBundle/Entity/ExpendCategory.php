<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpendCategory
 *
 * @ORM\Table(name="expend_category")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\ExpendCategoryRepository")
 */
class ExpendCategory
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
     * @ORM\OneToMany(targetEntity="Expend", mappedBy="expendCategory")
     */
   
    private $expends;
    
    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="expendCategories")
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
     * @return ExpendCategory
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
     * @return ExpendCategory
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
     * Set user
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\User $user
     * @return ExpendCategory
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
     * Add expends
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Expend $expends
     * @return ExpendCategory
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
}
