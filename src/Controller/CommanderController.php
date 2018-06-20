<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Adresse;
use App\Entity\Compte;
use App\Form\AjouterCarteType;
use App\Entity\CarteDeCredit;
use Symfony\Component\Translation\Translator;
class CommanderController extends Controller
{

    /**
     * @Route("/{_locale}/ajouter-adresse/{id}", name="commander_controller_ajouter_adresse")
     */
    public function ajouterAdresseAction(Request $request, $id,$_locale)
    {
        $adresse = new Adresse();
        $adresseForm = $this->createForm('App\Form\AdresseType', $adresse,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $adresseForm->handleRequest($request);
        if ($adresseForm->isSubmitted() && $adresseForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $compte = $em->getRepository(Compte::class)->find($id);
            $adresse->setCompte($compte);
            $em->persist($adresse);
            $em->flush();
            $compte->addAdresse($adresse);
            $request->getSession()->set('compte', $compte);
            return $this->redirectToRoute('panier_controller_verifier_connexion');
        }
        
        return $this->render('ajouter-adresse/ajouter-adresse.html.twig', array(
            'page' => 'ajouter-adresse',
            'adresseForm' => $adresseForm->createView()
        ));
    }

    /**
     * @Route("/{_locale}/moyen-de-livraison", name="mas_moyen_de_livraison")
     */
    public function moyenDeLivraisonAction(Request $request)
    {
        
        return $this->render('commander/moyen-de-livraison.html.twig', array(
            'page' => 'moyen-de-livraison'
        ));
    }

    /**
     * @Route("/{_locale}/caisse/{compteId}", name="commander_controller_aller_a_la_caisse")
     */
    public function allerAlaCaisseAction(Request $request, $compteId)
    {
        $carteDeCredit = new CarteDeCredit();
        $ajouterCarteForm = $this->createForm('App\Form\AjouterCarteType', $carteDeCredit,array('translator'=>new Translator($request->getLocale().'_'.strtoupper($request->getLocale()))));
        $ajouterCarteForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $compte = $em->getRepository(Compte::class)->find($compteId);
        if ($ajouterCarteForm->isSubmitted() && $ajouterCarteForm->isValid()) {
            $carteDeCredit->setCompte($compte);
            $numeroDeLaCarte = $carteDeCredit->getNumeroDeLaCarte();
            
            $em->persist($carteDeCredit);
            $compte->addCarteDeCredit($carteDeCredit);
            $em->flush();
            $request->getSession()->set('compte', $compte);
            return $this->redirectToRoute('commander_controller_aller_a_la_caisse', 
                array('compteId'=> $compteId));
        }
        $cartesDeCredit  = $compte->getCartesDeCredit();
        return $this->render('commander/payement.html.twig', array(
            'page' => 'payement',
            'ajouterCarteForm' => $ajouterCarteForm->createView(),
            'cartesDeCredit' => $cartesDeCredit
        ));
    }
        
    /**
     * @Route("/{_locale}/caisse/suprimer-carte/{carteId}", name="commander_controller_suprimer_carte")
     */
    public function suprimerCarteCaisseAction(Request $request, $carteId)
    {
        $em = $this->getDoctrine()->getManager();
        
        /**
         *
         * @var Adresse $adresse
         */
        $carteDeCredit = $em->getRepository(CarteDeCredit::class)->find($carteId);
        if($carteDeCredit != null){
            $em->remove($carteDeCredit);
            $em->flush();
        }
        return $this->redirectToRoute('commander_controller_aller_a_la_caisse',array(
            'compteId' => $carteDeCredit->getCompte()->getId()
        ));
    }
    
    /**
     * @Route("/{_locale}/caisse", name="commander_controller_achever_le_payement")
     */
    public function acheverPayementAction(Request $request)
    {
        
        return $this->render('commander/solution-banque.html.twig', array(
            'page' => 'solution'
        ));
    }
    
    /**
     * @Route("/add-delivery-adress-to-session/{id}", name="add_delivery_adress_to_session")
     */
    public function addDeliveryAdressToSession(Request $request,$id){
    	$jsonData = array();
    	if ($request->isXmlHttpRequest()) {
	    		$em = $this->getDoctrine()->getManager();
	    		$adress = $em->getRepository(Adresse::class)->find($id);
	    		$request->getSession()->set('deliveryAdress', $adress);
	    		$jsonData = array(
	    				'enableContinue' => 'true'
	    		);
    	}
    	return new JsonResponse($jsonData);
    }
    
    
    /**
     * @Route("/add-delivery-way-to-session/{way}", name="add_delivery_way_to_session")
     */
    public function addDeliveryWayToSession(Request $request,$way){
    	$jsonData = array();
    	if ($request->isXmlHttpRequest()) {
    		$request->getSession()->set('deliveryWay', $way);
    		$jsonData = array(
    				'way' => $way
    		);
    	}
    	return new JsonResponse($jsonData);
    }
    
    
    /**
     * @Route("/achat-terminer", name="commander_controller_terminer")
     */
    public function teminer(){
    	return $this->render('commander/achat-terminer.html.twig', array(
    			'page' => 'achat-terminer'
    	));
    }
}
