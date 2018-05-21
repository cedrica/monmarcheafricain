<?php
namespace  App\Entity;


class PanierItem{
    private $produit;
    private $quantite;

    /**
     * @return the $produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @return the $quantite
     */
    public function getQuantite()
    {
        return $this->quantite;
    }


    /**
     * @param field_type $produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;
    }

    /**
     * @param field_type $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    
    
}

?>