<?php

namespace HomeBudget\HomeBudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * expendCategory
 *
 * @ORM\Table(name="expend_category")
 * @ORM\Entity(repositoryClass="HomeBudget\HomeBudgetBundle\Repository\expendCategoryRepository")
 */
class expendCategory
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
   
    private $expendes;

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
     * @return expendCategory
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
     * @return expendCategory
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
}
