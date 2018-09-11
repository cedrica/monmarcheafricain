<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Adresse;
use App\Service\ToJson;
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
     * @Route("/add-delivery-adress-to-session", name="add_delivery_adress_to_session")
     */
    public function addDeliveryAdressToSession(Request $request){
    	$jsonData = array();
    	if ($request->isXmlHttpRequest()) {
    		$id = $request->request->get('adressId');
    		
    		$em = $this->getDoctrine()->getManager();
    		$adress = $em->getRepository(Adresse::class)->find($id);
    		$request->getSession()->set('deliveryAdress', $adress);
    		$jsonData = array(
    				'adressId' => $id
    		);
    	}
    	return new JsonResponse($jsonData);
    }
    
    /**
     * @Route("{_locale}/delivery-way", name="commander_controller_delivery_way")
     */
    public function deliveryWayAction(Request $request){
    	$session = $request->getSession();
    	$compte = $session->get('compte');
    	if($compte == null){
    		$em = $this->getDoctrine()->getManager();
    		$repository = $em->getRepository(Compte::class);
    		$compte = $repository->findOneBy(['id'=>$compte->getId()]);
    	}
    	
    	$adresses = $compte->getAdresses();
    	return $this->render('commander/commander.html.twig', array(
    			'adresses' => $adresses,
    			'page' => 'commander',
    			'connexion' => false,
    			'deliveryWay' => true,
    			'payment' => false,
    			'verifyAdress' => false,
    			'classconnexion' => 'not-active',
    			'classdeliveryway' => '',
    			'classdeliveryadress' => 'not-active',
    			'classpayment' => 'not-active'
    	));
    }
    
    /**
     * @Route("{_locale}/delivery-adress", name="commander_controller_delivery_adress")
     */
    public function deliveryAdressAction(Request $request){
    	$session = $request->getSession();
    	$compte = $session->get('compte');
    	if($compte == null){
    		$em = $this->getDoctrine()->getManager();
    		$repository = $em->getRepository(Compte::class);
    		$compte = $repository->findOneBy(['id'=>$compte->getId()]);
    	}
    	
    	$adresses = $compte->getAdresses();
    	return $this->render('commander/commander.html.twig', array(
    			'adresses' => $adresses,
    			'page' => 'commander',
    			'connexion' => false,
    			'deliveryWay' => false,
    			'payment' => false,
    			'verifyAdress' => true,
    			'classconnexion' => 'not-active',
    			'classdeliveryway' => 'not-active',
    			'classdeliveryadress' => '',
    			'classpayment' => 'not-active'
    	));
    }
    /**
     * @Route("{_locale}/payment", name="commander_controller_payment")
     */
    public function paymentAction(Request $request,$_locale){
    	$session = $request->getSession();
    	$compte = $session->get('compte');
    	$array = array(
    			'payerId' => $compte->getId(),
    			'payername' => $session->get('deliveryAdress'),
    			'deliveryWay' => $session->get('deliveryWay')
    	);
    	$i = 0;
    	foreach($session->get('panier')->getPanierItems() as $panierItem){
    		$array['item'.$i] = array(
    				'produit' => array(
    						'name' => $panierItem->getProduit()->getName(),
    						'price' => $panierItem->getProduit()->getPrix(),
    						'State' => $panierItem->getProduit()->getEtat(),
    						'Category' => $panierItem->getProduit()->getCategory(),
    						'Reference' => $panierItem->getProduit()->getReference(),
    						'Offer' => $panierItem->getProduit()->getAction(),
    						'Reduction' => $panierItem->getProduit()->getPourcentageDeRabait(),
    						'Periode' => $panierItem->getProduit()->getActionDebut().' - '.$panierItem->getProduit()->getActionFin(),
    						'Description'=> $panierItem->getProduit()->getDescriptionEN()
    				),
    				'quantite' => $panierItem->getQuantite()
    		);
    		$i++;
    	}
    	$transactions = json_encode($array);
    	var_dump($transactions);
    	return $this->render('commander/commander.html.twig', array(
    			'adresses' => array(),
    			'page' => 'commander',
    			'payment' => true,
    			'connexion' => false,
    			'deliveryWay' => false,
    			'verifyAdress' => false,
    			'classconnexion' => 'not-active',
    			'classdeliveryway' => 'not-active',
    			'classdeliveryadress' => 'not-active',
    			'classpayment' => '',
    			'transactions' => $transactions
    	));
    }
    
    /**
     * @Route("/add-delivery-way-to-session", name="add_delivery_way_to_session")
     */
    public function addDeliveryWayToSession(Request $request){
    	$way = $request->request->get('way');
    	$jsonData = array();
    	if ($request->isXmlHttpRequest()) {
    		$request->getSession()->set('deliveryWay', $way);
    		$jsonData = array(
    				'way' => $way
    		);
    	}
    	return new JsonResponse($jsonData);
    }

}
