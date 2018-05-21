<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdresseRepository")
 */
class Adresse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @var string
     * @ORM\Column(name="pays", type="string")
     */
    private $pays;
    
    /**
     * @var string
     * @ORM\Column(name="boitePostale", type="string")
     */
    private $boitePostale;
    
    /**
     * @var string
     * @ORM\Column(name="ville", type="string")
     */
    private $ville;
    
    /**
     * @var string
     * @ORM\Column(name="rueEtNr", type="string")
     */
    private $rueEtNr;
    /**
     * @ORM\ManyToOne(targetEntity="Compte", inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false,name="compte_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $compte;
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
     * Set pays
     *
     * @param string $pays
     *
     * @return Adresse
     */
    public function setPays($pays)
    {
    	$this->pays = $pays;
    	
    	return $this;
    }
    
    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
    	return $this->pays;
    }
    
    /**
     * Set boitePostale
     *
     * @param string $boitePostale
     *
     */
    public function setBoitePostale($boitePostale)
    {
    	$this->boitePostale = $boitePostale;
    	
    }
    
    /**
     * Get boitePostale
     *
     * @return string
     */
    public function getBoitePostale()
    {
    	return $this->boitePostale;
    }
    
    /**
     * Set ville
     *
     * @param string $ville
     *
     */
    public function setVille($ville)
    {
    	$this->ville = $ville;
    }
    
    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
    	return $this->ville;
    }
    
    /**
     * Set rueEtNr
     *
     * @param string $rueEtNr
     *
     * @return $rueEtNr
     */
    public function setRueEtNr($rueEtNr)
    {
    	$this->rueEtNr = $rueEtNr;
    }
    
    /**
     * Get rueEtNr
     *
     * @return string
     */
    public function getRueEtNr()
    {
    	return $this->rueEtNr;
    }
    /**
     * @return Compte $compte
     */
    public function getCompte()
    {
    	return $this->compte;
    }
    
    /**
     * @param Compte $compte
     */
    public function setCompte($compte)
    {
    	$this->compte = $compte;
    	
    }
}

