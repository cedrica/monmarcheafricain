<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteRepository")
 */
class Recette
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\OneToMany(targetEntity="Ingredient", mappedBy="recette")
     */
    private $ingredients;
    
    /**
     *@ORM\OneToMany(targetEntity="Step", mappedBy="recette")
     */
    private $steps;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    private $image;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    private $nom;
    
    public function __construct()
    {
    	$this->ingredients = new ArrayCollection();
    	$this->steps = new ArrayCollection();
    }
    
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
     * Set ingredients
     *
     * @param string $ingredients
     *
     * @return Recette
     */
    public function setIngredients($ingredients)
    {
    	$this->ingredients = $ingredients;
    	
    	return $this;
    }
    
    /**
     * Get ingredients
     *
     * @return string
     */
    public function getIngredients()
    {
    	return $this->ingredients;
    }
    
    /**
     * Set steps
     *
     * @param string $steps
     *
     * @return Recette
     */
    public function setSteps($steps)
    {
    	$this->steps = $steps;
    	
    	return $this;
    }
    
    /**
     * Get steps
     *
     * @return string
     */
    public function getSteps()
    {
    	return $this->steps;
    }
    
    /**
     * Set image
     *
     */
    public function setImage($image)
    {
    	$this->image = $image;
    	
    	return $this;
    }
    
    /**
     * Get image
     *
     */
    public function getImage()
    {
    	return $this->image;
    }
    
    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Recette
     */
    public function setNom($nom)
    {
    	$this->nom = $nom;
    	
    	return $this;
    }
    
    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
    	return $this->nom;
    }
    /**
     * @param field_type $id
     */
    public function setId($id)
    {
    	$this->id = $id;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecette($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecette() === $this) {
                $ingredient->setRecette(null);
            }
        }

        return $this;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecette($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->contains($step)) {
            $this->steps->removeElement($step);
            // set the owning side to null (unless already changed)
            if ($step->getRecette() === $this) {
                $step->setRecette(null);
            }
        }

        return $this;
    }
    
    
    
}

