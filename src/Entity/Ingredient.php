<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @var string @ORM\Column(name="nom", type="string")
     */
    private $nom;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Recette", inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $recette;
    
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Ingredient
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
     * @return Recette
     */
    public function getRecette()
    {
    	return $this->recette;
    }
    
    
    
    /**
     * @param  $recette
     */
    public function setRecette($recette)
    {
    	$this->recette = $recette;
    }
    
    
    
}

