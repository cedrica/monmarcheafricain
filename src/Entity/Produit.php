<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nameDE", type="text")
     */
    private $nameDE;
    
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="prix", type="decimal", length=25)
     */
    private $prix;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=10, nullable=true)
     */
    private $etat;
    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=50)
     */
    private $categorie;
    
    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=50)
     */
    private $category;
    
    /**
     * @var string
     *
     * @ORM\Column(name="kategorie", type="string", length=50)
     */
    private $kategorie;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=50, nullable=false)
     */
    private $reference;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
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
     * @Assert\NotBlank(message="Please, upload the product brochure as a jpeg file.")
     * @Assert\File(mimeTypes={ "image/jpeg" })
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
     * @var string
     *
     * @ORM\Column(name="descriptionFR", type="string", length=255, nullable=true)
     */
    private $descriptionFR;
    /**
     * @var string
     *
     * @ORM\Column(name="descriptionEN", type="string", length=255, nullable=true)
     */
    private $descriptionEN;
    /**
     * @var string
     *
     * @ORM\Column(name="descriptionDE", type="string", length=255, nullable=true)
     */
    private $descriptionDE;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $preis;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activated;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aktiviert;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $offer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $angebot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $menge;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $available;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $verfuegbar;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rabatt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $reduction;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $offerStartDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $offerEndDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $angebotStartDatum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $angebotEndDatum;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $zustand;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;


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
     * Set image
     *
     */
    public function setName($name)
    {
    	$this->name = $name;
    }
    /**
     * Get image
     *
     */
    public function getName()
    {
    	return $this->name;
    }
    
    
    /**
     * Set image
     *
     */
    public function setNameDE($nameDE)
    {
    	$this->nameDE = $nameDE;
    }
    /**
     * Get image
     *
     */
    public function getNameDE()
    {
    	return $this->nameDE;
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
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Produit
     */
    public function setCategory($category)
    {
    	$this->category = $category;
    }
    
    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
    	return $this->category;
    }
    
    
    /**
     * Set kategorie
     *
     * @param string $kategorie
     *
     */
    public function setKategorie($kategorie)
    {
    	$this->kategorie = $kategorie;
    }
    
    /**
     * Get kategorie
     *
     * @return string
     */
    public function getKategorie()
    {
    	return $this->kategorie;
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

    public function getDescriptionFR()
    {
    	return $this->descriptionFR;
    }
    
    public function setDescriptionFR($descriptionFR)
    {
    	$this->descriptionFR = $descriptionFR;
    	
    }
    
    public function getDescriptionEN()
    {
    	return $this->descriptionEN;
    }
    
    public function setDescriptionEN($descriptionEN)
    {
    	$this->descriptionEN = $descriptionEN;
    	
    }
    
    public function getDescriptionDE()
    {
    	return $this->descriptionDE;
    }
    
    public function setDescriptionDE($descriptionDE)
    {
    	$this->descriptionDE = $descriptionDE;
    	
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPreis(): ?float
    {
        return $this->preis;
    }

    public function setPreis(float $preis): self
    {
        $this->preis = $preis;

        return $this;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(?bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getAktiviert(): ?bool
    {
        return $this->aktiviert;
    }

    public function setAktiviert(?bool $aktiviert): self
    {
        $this->aktiviert = $aktiviert;

        return $this;
    }

    public function getOffer(): ?bool
    {
        return $this->offer;
    }

    public function setOffer(?bool $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getAngebot(): ?bool
    {
        return $this->angebot;
    }

    public function setAngebot(?bool $angebot): self
    {
        $this->angebot = $angebot;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getMenge(): ?int
    {
        return $this->menge;
    }

    public function setMenge(?int $menge): self
    {
        $this->menge = $menge;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(?bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getVerfuegbar(): ?bool
    {
        return $this->verfuegbar;
    }

    public function setVerfuegbar(?bool $verfuegbar): self
    {
        $this->verfuegbar = $verfuegbar;

        return $this;
    }

    public function getRabatt(): ?float
    {
        return $this->rabatt;
    }

    public function setRabatt(?float $rabatt): self
    {
        $this->rabatt = $rabatt;

        return $this;
    }

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(?float $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getOfferStartDate(): ?\DateTimeInterface
    {
        return $this->offerStartDate;
    }

    public function setOfferStartDate(?\DateTimeInterface $offerStartDate): self
    {
        $this->offerStartDate = $offerStartDate;

        return $this;
    }

    public function getOfferEndDate(): ?\DateTimeInterface
    {
        return $this->offerEndDate;
    }

    public function setOfferEndDate(?\DateTimeInterface $offerEndDate): self
    {
        $this->offerEndDate = $offerEndDate;

        return $this;
    }

    public function getAngebotStartDatum(): ?\DateTimeInterface
    {
        return $this->angebotStartDatum;
    }

    public function setAngebotStartDatum(?\DateTimeInterface $angebotStartDatum): self
    {
        $this->angebotStartDatum = $angebotStartDatum;

        return $this;
    }

    public function getAngebotEndDatum(): ?\DateTimeInterface
    {
        return $this->angebotEndDatum;
    }

    public function setAngebotEndDatum(?\DateTimeInterface $angebotEndDatum): self
    {
        $this->angebotEndDatum = $angebotEndDatum;

        return $this;
    }

    public function getZustand(): ?string
    {
        return $this->zustand;
    }

    public function setZustand(?string $zustand): self
    {
        $this->zustand = $zustand;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}


