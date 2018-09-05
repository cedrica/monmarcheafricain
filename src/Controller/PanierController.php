<?php
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ControllerHelper;
use App\Entity\Panier;
use App\Entity\Compte;

class PanierController extends Controller
{

    /**
     * @Route("{_locale}/panier", name="mas_panier")
     */
    public function panierAction(Request $request, ControllerHelper $helper)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        if($panier == null){
            $panier = new Panier();
            $session->set('panier', $panier);
        }
        
        $fraisDeTransport = 0;
        $totalProduit = 0;
        $produitId = $request->query->get('id');
        if ($produitId != null){
            foreach ($panier->getPanierItems() as $item) {
                if ($item->getProduit()->getId() == $produitId) {
                    $panier->getPanierItems()->removeElement($item);
                    break;
                }
            }
            $session->set('panier', $panier);
            $quantite = $helper->caculeLaQuantite($panier);
            $session->set('quantite', $quantite);
        } else {
            foreach ($panier->getPanierItems() as $item) {
                $totalProduit += $item->getProduit()->getPrix() * $item->getQuantite();
            }
        }
        return $this->render('panier/panier.html.twig', array(
            'panier' => $panier,
            'page' => 'panier',
            'totalProduit' => $totalProduit,
            'fraisDeTransport' => $fraisDeTransport
        ));
    }
    
    /**
     * @Route("{_locale}/commander", name="panier_controller_verifier_connexion")
     */
    public function checkConnectionAction(Request $request, ControllerHelper $helper)
    {
        $request->getSession()->set('allerALaCaisse', true);
        $session = $request->getSession();
        $compte = $session->get('compte');
        if($compte == null){
            return $this->redirectToRoute('connexion_controller_connexion');
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Compte::class);
        
        $compte = $repository->findOneBy(['id'=>$compte->getId()]);
        
        $adresses = $compte->getAdresses();
        return $this->render('commander/commander.html.twig', array(
            'page' => 'commander',
            'adresses' => $adresses,
        		'payment' => false,
        		'classconnexion' => '',
        		'classdeliveryway' => 'not-active',
        		'classdeliveryadress' => 'not-active',
        		'classpayment' => 'not-active'
        ));
    }
    
    
}
