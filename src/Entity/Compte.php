<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 */
class Compte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *  @ORM\Column(name="titre", type="string", nullable=true)
     */
    private $titre;
    
    /**
     * @var string
     * @ORM\Column(name="nom", type="string")
     */
    private $nom;
    
    /**
     *
     * @var string
     * @ORM\Column(name="role", type="string")
     */
    private $role;
    /**
     * @var string
     * @ORM\Column(name="prenom", type="string")
     */
    private $prenom;
    
    /**
     * @var \Date
     * @ORM\Column(name="dateDeNaissance", type="date")
     */
    private $dateDeNaissance;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Adresse", mappedBy="compte",cascade={"persist"}, orphanRemoval=true)
     */
    private $adresses;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Login", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $login;

    
    
    
    
    public function  __construct(){
    	$this->adresses = new ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     *
     */
    public function setTitre($titre)
    {
    	$this->titre = $titre;
    }
    
    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
    	return $this->titre;
    }
    
    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Compte
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
     * Set prenom
     *
     * @param string $prenom
     *
     *
     */
    public function setPrenom($prenom)
    {
    	$this->prenom = $prenom;
    	
    }
    
    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
    	return $this->prenom;
    }
    
    /**
     * Set dateDeNaissance
     *
     * @param \DateTime $dateDeNaissance
     *
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
    	$this->dateDeNaissance = $dateDeNaissance;
    	
    }
    
    /**
     * Get dateDeNaissance
     *
     * @return \DateTime
     */
    public function getDateDeNaissance()
    {
    	return $this->dateDeNaissance;
    }
    /**
     * @return Collection|Adresse[]
     */
    public function getAdresses()
    {
    	return $this->adresses;
    }
    
    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $adresses
     */
    public function setAdresses($adresses)
    {
    	$this->adresses = $adresses;
    }
    
    
    public function addAdresse(Adresse $adresse)
    {
    	if ($this->adresses->contains($adresse)) {
    		return;
    	}
    	
    	$this->adresses[] = $adresse;
    }
    
    public function removeAdresse(Adresse $adresse)
    {
    	$this->adresses->removeElement($adresse);
    }
    /**
     * @return string
     */
    public function getRole()
    {
    	return $this->role;
    }
    
    /**
     * @param string $role
     */
    public function setRole($role)
    {
    	$this->role = $role;
    }

    public function addAdress(Adresse $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setCompte($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): self
    {
        if ($this->adresses->contains($adress)) {
            $this->adresses->removeElement($adress);
            // set the owning side to null (unless already changed)
            if ($adress->getCompte() === $this) {
                $adress->setCompte(null);
            }
        }

        return $this;
    }

    public function getLogin(): ?self
    {
        return $this->login;
    }

    public function setLogin(self $login): self
    {
        $this->login = $login;

        return $this;
    }

}

