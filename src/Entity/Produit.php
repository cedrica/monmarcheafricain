<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="text")
     */
    private $nom;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="prix", type="decimal", length=255)
     */
    private $prix;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;
    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;
    
    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=false)
     */
    private $reference;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;
    
    
    /**
     * @var bool
     *
     * @ORM\Column(name="disponible", type="boolean")
     */
    private $disponible;
    
    /**
     * @ORM\Column(type="string")
     *
     */
    private $image;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $action;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pourcentageDeRabait;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actionDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actionFin;
    
    
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
     * @return Produit
     */
    public function setNom($nom)
    {
    	$this->nom = $nom;
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
     * Set prix
     *
     * @param decimal $prix
     *
     * @return Produit
     */
    public function setPrix($prix)
    {
    	$this->prix = $prix;
    	
    }
    
    /**
     * Get prix
     *
     * @return decimal
     */
    public function getPrix()
    {
    	return $this->prix;
    }
    
    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Produit
     */
    public function setEtat($etat)
    {
    	$this->etat = $etat;
    }
    
    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
    	return $this->etat;
    }
    
    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Produit
     */
    public function setCategorie($categorie)
    {
    	$this->categorie = $categorie;
    }
    
    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
    	return $this->categorie;
    }
    
    /**
     * Set disponible
     *
     * @param boolean $disponible
     *
     * @return Produit
     */
    public function setDisponible($disponible)
    {
    	$this->disponible = $disponible;
    }
    
    /**
     * Get disponible
     *
     * @return bool
     */
    public function getDisponible()
    {
    	return $this->disponible;
    }
    /**
     * Set image
     *
     */
    public function setImage($image)
    {
    	$this->image = $image;
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
     * @return  $reference
     */
    public function getReference()
    {
    	return $this->reference;
    }
    
    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
    	$this->reference = $reference;
    }
    /**
     * @return  $quantite
     */
    public function getQuantite()
    {
    	return $this->quantite;
    }
    
    /**
     * @param number $quantite
     */
    public function setQuantite($quantite)
    {
    	$this->quantite = $quantite;
    }
    
    /**
     * @return the $actif
     */
    public function getActif()
    {
    	return $this->actif;
    }
    
    /**
     * @param boolean $actif
     */
    public function setActif($actif)
    {
    	$this->actif = $actif;
    }

    public function getAction(): ?bool
    {
        return $this->action;
    }

    public function setAction(?bool $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getPourcentageDeRabait(): ?float
    {
        return $this->pourcentageDeRabait;
    }

    public function setPourcentageDeRabait(?float $pourcentageDeRabait): self
    {
        $this->pourcentageDeRabait = $pourcentageDeRabait;

        return $this;
    }

    public function getActionDebut(): ?\DateTime
    {
        return $this->actionDebut;
    }

    public function setActionDebut(?\DateTime $actionDebut): self
    {
        $this->actionDebut = $actionDebut;

        return $this;
    }

    public function getActionFin(): ?\DateTime
    {
        return $this->actionFin;
    }

    public function setActionFin(?\DateTime $actionFin): self
    {
        $this->actionFin = $actionFin;

        return $this;
    }
    
}


