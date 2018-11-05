<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Recette;
use App\Service\ControllerHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Compte;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConfigurationProduitController extends Controller
{

    /**
     * @Route("/{_locale}/editer-produit/{id}/{errors}", name="configuration_produit_controller_editer")
     */
	public function editProduitAction(Request $request, ControllerHelper $helper,$id, $_locale)
    {
        $produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $produitRepositorty->find($id);
        $categoryNodeList = $helper->convertXmlToObjectList('catalogs/categories.xml');
        $errors = $request->get('errors');
        //echo $errors;
        return $this->render('configuration/produits/editer-produit.html.twig', array(
            'page' => 'editer-produit',
        		'produit' => $produit,
        		'errors' => $errors,
        		'categoryNodeList' => $categoryNodeList
        ));
    }

    /**
     * @Route("/{_locale}/edit-product", name="configuration_produit_controller_save_edited_product")
     */
    public function saveEditedProduct(Request $request,ValidatorInterface $validator, ControllerHelper $helper)
    {
    	if($request->isMethod('POST') ){
    		$id = $request->query->get('id');
    		$em = $this->getDoctrine()->getManager();
    		$produit = $em->getRepository(Produit::class)->find($id);
    		$produit = self::makeProduit($produit, $request,true);
    		$errors = $validator->validate($produit);
    		if (count($errors) > 0) {
    			$categoryNodeList = $helper->convertXmlToObjectList('catalogs/categories.xml');
    			return $this->render('configuration/produits/editer-produit.html.twig', array(
    					'page' => 'editer-produit',
    					'produit' => $produit,
    					'errors' => $errors,
    					'categoryNodeList' => $categoryNodeList
    			));
    		}
    		$em->flush();
    		
    	}
    	return $this->redirectToRoute('configuration_controller_init',
    			array(
    					'_locale'=>$request->getLocale(),
    					'alertType' => 'succes',
    					'message' => 'Produit editer avec succes',
    					'errors' => $errors
    			));
    }

    public function handleRequestAndSubmit(Request $request,$form,$produitRepositorty,$produit){
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    		$ancienneImage = $produit->getImage();
    		$em = $this->getDoctrine()->getManager();
    		$fs = new FileSystem();
    		$fs->remove($this->getParameter('brochures_directory').'/'.$ancienneImage);
    		/** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
    		$image = $produit->getImage();
    		$fileName = md5(uniqid()) . '.' . $image->guessExtension();
    		$image->move($this->getParameter('brochures_directory'), $fileName);
    		$produit->setImage($fileName);
    		$em->persist($produit);
    		$em->flush();
    		
    		$produits = $produitRepositorty->findAll();
    		return $this->redirectToRoute('configuration_controller_init', array(
    				'page' => 'configuration',
    				'message' => 'Le produit a été édité avec succès',
    				'alertType' => 'succes',
    				'message' => 'le produit ' . $produit->getNom() . ' a été sauvgardé avec succes',
    				'cfg' => 'prod',
    				'produits' => $produits
    		));
    		
    	}
    	return $form;
    }
    /**
     * @Route("/{_locale}/delete-produit/{id}", name="configuration_produit_controller_delete")
     */
    public function deleteProduitAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$produit = $em->getRepository(Produit::class)->find($id);
    	$em->remove($produit);
    	$em->flush();
    	return $this->redirectToRoute('configuration_controller_init',
    			array(
    					'cfg' => 'prod',
    					'_locale'=>$request->getLocale(),
    					'alertType' => 'succes',
    					'message' => 'Produit enlevée avec succes'
    			));
    }
    


    /**
     * @Route("/{_locale}/create-product", name="configuration_produit_controller_create_product")
     */
    public function createProduct(Request $request)
    {
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $produit = new Produit();
            $produit = self::makeProduit($produit, $request,false);
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('configuration_controller_init',
                array(
                    '_locale'=>$request->getLocale(),
                    'alertType' => 'succes',
                    'message' => 'Produit editer avec succes'
                ));
        }
        return  $this->redirectToRoute('configuration_controller_init');
    }
    
    public function makeProduit($produit, $request,$editModus){
    	$nom = $request->request->get('nom');
    	$name = $request->request->get('name');
    	$nameDE = $request->request->get('nameDE');
    	
    	$preis = $request->request->get('preis');
    	$preis = ($preis == "")? 0:$preis;
    	$prix = $preis;
    	$price = $preis;
    	
    	$zustand = $request->request->get('zustand');
    	$etat =$zustand;
    	$state = $zustand;
    	
    	$angebot =  $request->request->get('angebot');
    	$angebot = ($angebot != "1")? false:true;
    	$action = $angebot;
    	$offer = $angebot;
    	
    	$menge  = $request->request->get('menge');
    	$menge = ($menge == "")? 0:$menge;
    	$quantity = $menge;
    	$quantite = $menge;
    	
    	$category = $request->request->get('category');
    	$categorie = $request->request->get('categorie');
    	$kategorie = $request->request->get('kategorie');
    	
    	$verfuegbar = $request->request->get('verfuegbar');
    	$verfuegbar = ($verfuegbar != "1")? false:true;
    	$available = $verfuegbar;
    	$disponible = $verfuegbar;
    	
    	$rabatt =  $request->request->get('rabatt');
    	$rabatt = ($rabatt == "")? 0:$rabatt;
    	$pourcentageDeRabait = $rabatt;
    	$reduction = $rabatt;
    	
    	$angebotStartDatum =  $request->request->get('angebotStartDatum');
    	$angebotStartDatum = ($angebotStartDatum == "")? null:$angebotStartDatum;
    	$actionDebut = $angebotStartDatum;
    	$offerStartDate = $angebotStartDatum;
    	
    	$angebotEndDatum = $request->request->get('angebotEndDatum');
    	$angebotEndDatum = ($angebotEndDatum == "")? null:$angebotEndDatum;
    	$actionFin = $angebotEndDatum;
    	$offerEndDate = $angebotEndDatum;
    	
    	$descriptionFR = $request->request->get('descriptionFR');
    	$descriptionEN = $request->request->get('descriptionEN');
    	$descriptionDE = $request->request->get('descriptionDE');
    	
    	$fileName = $_FILES['image']['name'];
    	$bfileName = basename($fileName);
    	$tmpFile = $_FILES['image']['tmp_name'];
    	move_uploaded_file($tmpFile,"uploads/brochures/$bfileName");
    	// This way to remove file work
    	// $fs = new Filesystem();
    	// $fs->remove("uploads/ebff62e127db54f09058ac980d029609.png");
    	// ///
    	
    	$produit->setNom($nom);
    	$produit->setName($name);
    	$produit->setNameDE($nameDE);
    	
    	$produit->setCategorie($categorie);
    	$produit->setCategory($category);
    	$produit->setKategorie($kategorie);
    	
    	$produit->setPrix($prix);
    	$produit->setPrice($price);
    	$produit->setPreis($preis);
    	
    	$produit->setEtat($etat);
    	$produit->setState($state);
    	$produit->setZustand($zustand);
    	
    	$produit->setQuantite($quantite);
    	$produit->setQuantity($quantity);
    	$produit->setMenge($menge);
    	
    	$produit->setDisponible($disponible);
    	$produit->setAvailable($available);
    	$produit->setVerfuegbar($verfuegbar);
    	
    	$produit->setImage($fileName);
    	
    	$produit->setAction($action);
    	$produit->setAngebot($angebot);
    	$produit->setOffer($offer);
    	
    	$produit->setPourcentageDeRabait($pourcentageDeRabait);
    	$produit->setReduction($reduction);
    	$produit->setRabatt($rabatt);
    	
    	$produit->setActionDebut($actionDebut);
    	$produit->setOfferStartDate($offerStartDate);
    	$produit->setAngebotStartDatum($angebotStartDatum);
    	
    	$produit->setActionFin($actionFin);
    	$produit->setOfferEndDate($offerEndDate);
    	$produit->setAngebotEndDatum($angebotEndDatum);
    	
    	$produit->setDescriptionFR($descriptionFR);
    	$produit->setDescriptionEN($descriptionEN);
    	$produit->setDescriptionDE($descriptionDE);
    	
    	if(!$editModus){
    		$dateTime = new \DateTime();
    		$format = 'Y-m-dH:i:s';
    		$formatedDT = $dateTime->format($format);
    		$formatedDT = str_replace("-", "", $formatedDT);
    		$formatedDT = str_replace(":", "", $formatedDT);
    		
    		$produit->setReference($formatedDT);
    		$produit->setCreatedAt(new \DateTime());
    	}
    	return $produit;
    }

}
