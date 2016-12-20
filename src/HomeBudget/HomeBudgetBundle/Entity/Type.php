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
     *@ORM\OneToMany(targetEntity="Acount", mappedBy="type")
     * @var type 
     */
    private $acounts;
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
        $this->acounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add acounts
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Acount $acounts
     * @return Type
     */
    public function addAcount(\HomeBudget\HomeBudgetBundle\Entity\Acount $acounts)
    {
        $this->acounts[] = $acounts;

        return $this;
    }

    /**
     * Remove acounts
     *
     * @param \HomeBudget\HomeBudgetBundle\Entity\Acount $acounts
     */
    public function removeAcount(\HomeBudget\HomeBudgetBundle\Entity\Acount $acounts)
    {
        $this->acounts->removeElement($acounts);
    }

    /**
     * Get acounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAcounts()
    {
        return $this->acounts;
    }
}
