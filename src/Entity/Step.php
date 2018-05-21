<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StepRepository")
 */
class Step
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="description", type="string")
     */
    private $description;
    
    
    /**
     * @var string
     * @ORM\Column(name="temps")
     */
    private $temps;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recette", inversedBy="steps")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $recette;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $position;

    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Set description
     *
     * @param string $description
     *
     * @return Step
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
     * Set temps
     *
     * @param string $temps
     *
     * @return Step
     */
    public function setTemps($temps)
    {
    	$this->temps = $temps;
    	
    	return $this;
    }
    
    /**
     * Get temps
     *
     * @return string
     */
    public function getTemps()
    {
    	return $this->temps;
    }
    
    /**
     * @return the $recette
     */
    public function getRecette()
    {
    	return $this->recette;
    }
    
    /**
     * @param field_type $recette
     */
    public function setRecette($recette)
    {
    	$this->recette = $recette;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    
}

