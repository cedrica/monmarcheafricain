<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarteDeCreditRepository")
 */
class CarteDeCredit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *  @ORM\Column(name="nomDeLaCarte", type="string", nullable=true)
     */
    private $nomDeLaCarte;
    
    /**
     * @var string
     * @ORM\Column(name="numeroDeLaCarte", type="string")
     */
    private $numeroDeLaCarte;
    
    /**
     * @var \Date
     * @ORM\Column(name="dateDExpiration", type="date")
     */
    private $dateDExpiration;
    
    /**
     * @ORM\ManyToOne(targetEntity="Compte", inversedBy="cartesDeCredit")
     * @ORM\JoinColumn(nullable=false,name="compte_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $compte;
    
    /**
     *
     * @var boolean
     * @ORM\Column(name="defaultCarte", type="boolean")
     */
    private $defaultCarte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    
    public function  __construct(){
    	$this->setDefaultCarte(FALSE);
    	
    }
    /**
     * @return number
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * @return string
     */
    public function getNomDeLaCarte()
    {
    	return $this->nomDeLaCarte;
    }
    
    /**
     * @return string
     */
    public function getNumeroDeLaCarte()
    {
    	return $this->numeroDeLaCarte;
    }
    
    /**
     * @return Date
     */
    public function getDateDExpiration()
    {
    	return $this->dateDExpiration;
    }
    
    /**
     * @param number $id
     */
    public function setId($id)
    {
    	$this->id = $id;
    }
    
    /**
     * @param string $nomDeLaCarte
     */
    public function setNomDeLaCarte($nomDeLaCarte)
    {
    	$this->nomDeLaCarte = $nomDeLaCarte;
    }
    
    /**
     * @param string $numeroDeLaCarte
     */
    public function setNumeroDeLaCarte($numeroDeLaCarte)
    {
    	$this->numeroDeLaCarte = $numeroDeLaCarte;
    }
    
    /**
     * @param Date $dateDExpiration
     */
    public function setDateDExpiration($dateDExpiration)
    {
    	$this->dateDExpiration = $dateDExpiration;
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
    /**
     * @return boolean
     */
    public function isDefaultCarte()
    {
    	return $this->defaultCarte;
    }
    
    /**
     * @param boolean $defaultCarte
     */
    public function setDefaultCarte($defaultCarte)
    {
    	$this->defaultCarte = $defaultCarte;
    }

    public function getDefaultCarte(): ?bool
    {
        return $this->defaultCarte;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
    
}

