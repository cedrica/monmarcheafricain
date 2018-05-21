<?php
namespace  App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Panier{
    
    private  $panierItems;
    public function __construct()
    {
        $this->panierItems = new ArrayCollection();
    }
    /**
     * @return the $panierItems
     */
    public function getPanierItems()
    {
        return $this->panierItems;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $panierItems
     */
    public function setPanierItems($panierItems)
    {
        $this->panierItems = $panierItems;
    }

   
}

?>