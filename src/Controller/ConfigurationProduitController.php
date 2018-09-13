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

class ConfigurationProduitController extends Controller
{

    /**
     * @Route("/{_locale}/editer-produit/{id}", name="configuration_produit_controller_editer")
     */
    public function editProduitAction(Request $request, $id, $_locale)
    {
    	$produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
    	$produit = $produitRepositorty->find($id);
    	$editProduitForm = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
    	$editProduitForm = self::handleRequestAndSubmit($request,$editProduitForm,$produitRepositorty,$produit);
    	
        return $this->render('configuration/produits/editer-produit.html.twig', array(
            'page' => 'editer-produit',
        	'produit' => $produit,
        	'editProduitForm' => $editProduitForm->createView()
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
    		return $this->redirectToRoute('configuration_controller_init_view', array(
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
    	return $this->redirectToRoute('configuration_controller_init_view',
    			array(
    					'cfg' => 'prod',
    					'_locale'=>$request->getLocale(),
    					'alertType' => 'succes',
    					'message' => 'Produit enlevée avec succes'
    			));
    }
    
    /**
     * @Route("/{_locale}/save-product/{id}", name="configuration_produit_controller_save_edited_product")
     */
    public function saveEditedProduct(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$produit = $em->getRepository(Produit::class)->find($id);
    	
    	$nom = $request->request->get('nom');
    	$name = $request->request->get('name');
    	$nameDE = $request->request->get('nameDE');
    	$categorie = $request->request->get('categorie');
    	$category = $request->request->get('category');
    	$kategorie = $request->request->get('kategorie');
    	$prix = $request->request->get('prix');
    	$etat = $request->request->get('etat');
    	$quantite = $request->request->get('quantite');
    	$disponible = $request->request->get('disponible');
    	$image = $request->request->get('image');
    	$actif = $request->request->get('actif');
    	$action = $request->request->get('action');
    	$pourcentageDeRabait = $request->request->get('pourcentageDeRabait');
    	$actionDebut = $request->request->get('actionDebut');
    	$actionFin = $request->request->get('actionFin');
    	$descriptionFR = $request->request->get('descriptionFR');
    	$descriptionEN = $request->request->get('descriptionEN');
    	$descriptionDE = $request->request->get('descriptionDE');
    	
    	$produit->setNom($nom);
    	$produit->setName($name);
    	$produit->setNameDE($nameDE);
    	$produit->setCategorie($categorie);
    	$produit->setCategory($category);
    	$produit->setKategorie($kategorie);
    	$produit->setPrix($prix);
    	$produit->setEtat($etat);
    	$produit->setQuantite($quantite);
    	$produit->setDisponible($disponible);
    	$produit->setImage($image);
    	$produit->setActif($actif);
    	$produit->setAction($action);
    	$produit->setPourcentageDeRabait($pourcentageDeRabait);
    	$produit->setActionDebut($actionDebut);
    	$produit->setActionFin($actionFin);
    	$produit->setDescriptionFR($descriptionFR);
    	$produit->setDescriptionEN($descriptionEN);
    	$produit->setDescriptionDE($descriptionDE);
    	
    	$em->persist($produit);
    	$em->flush();
    	return $this->redirectToRoute('configuration_controller_init_view',
    			array(
    					'cfg' => 'prod',
    					'_locale'=>$request->getLocale(),
    					'alertType' => 'succes',
    					'message' => 'Produit enlevée avec succes'
    			));
    }

}
