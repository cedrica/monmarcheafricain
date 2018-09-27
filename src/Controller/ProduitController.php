<?php
namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Service\ControllerHelper;
use App\Entity\PanierItem;
use Symfony\Component\DomCrawler\Image;
use App\Entity\Panier;

/**
 * Produit controller.
 */
class ProduitController extends Controller
{

    /**
     * @Route("{_locale}/produit/{id}",name="produit_controller_produit")
     *
     * @method ({"GET", "POST"})
     */
    public function indexAction(Request $request,  $id, ControllerHelper $helper)
    {
    	$quantite = $request->query->get('quantite');
    	$produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
    	$produit = $produitRepositorty->find($id);
    	$quantite = self::ajouterLeproduitAuPanier($request, $produit,$helper,$quantite);
        return $this->render('produit/produit.html.twig', array(
            'page' => 'produit',
            'produit' => $produit,
            'quantite' => $quantite
        ));
    }
    
    /**
     * @Route("{_locale}/actualise/{cat}/{id}/{affichage}",name="produit_controller_actualise")
     *
     * @method ({"GET", "POST"})
     */
    public function actualisePanierAction(Request $request, $cat, $id, $affichage, ControllerHelper $helper)
    {
    	$produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
    	$produit = $produitRepositorty->find($id);
    	$quantite = self::ajouterLeproduitAuPanier($request, $produit,$helper,1);
    	return $this->redirectToRoute('categories_controller_categories', array(
    			'catId' => $cat,
    			'_locale' => $request->getLocale(),
    			'affichage' => $affichage,
    			'activehome' => '',
    			'activecategorie' => 'active',
    			'activerecettes' => '',
    			'activelivraison' => '',
    			'activecontact' => ''
    	));
    }
    public function ajouterLeproduitAuPanier(Request $request, Produit $produit, ControllerHelper $helper,$quantite) {
    	$session = $request->getSession();
    	$panier = $session->get('panier');
    	if ($panier == null) {
    		$panier = new Panier();
    		$panierItem = new PanierItem();
    		$panierItem->setProduit($produit);
    		$panierItem->setQuantite($quantite);
    		$panier->getPanierItems()->add($panierItem);
    	} else {
    		$exit = false;
    		if ($panier->getPanierItems() != null && ! $panier->getPanierItems()->isEmpty()) {
    			foreach ($panier->getPanierItems() as $prodi) {
    				if ($produit->getId() == $prodi->getProduit()->getId()) {
    					$panierItem = new PanierItem();
    					$panierItem->setProduit($produit);
    					$panierItem->setQuantite($prodi->getQuantite() + $quantite);
    					$panier->getPanierItems()->removeElement($prodi);
    					$panier->getPanierItems()->add($panierItem);
    					$exit = true;
    					break;
    				}
    			}
    			if (! $exit) {
    				$panierItem = new PanierItem();
    				$panierItem->setProduit($produit);
    				$panierItem->setQuantite($quantite);
    				$panier->getPanierItems()->add($panierItem);
    			}
    		} else {
    			$panierItem = new PanierItem();
    			$panierItem->setProduit($produit);
    			$panierItem->setQuantite($quantite);
    			$panier->getPanierItems()->add($panierItem);
    		}
    	}
    	$session->set('panier', $panier);
    	$quantite = $helper->caculeLaQuantite($panier);
    	$session->set('quantite', $quantite);
    	return $quantite;
    }

}
