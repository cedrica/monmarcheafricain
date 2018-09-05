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
    	$editProduitForm = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale)),
    			'en' => true,
    			'fr' => false,
    			'de' => false
    	));
    	$editProduitForm = self::handleRequestAndSubmit($request,$editProduitForm,$produitRepositorty,$produit);
    	
    	
    	$editProduitFormDE = $this->createForm('App\Form\ProduitType', $produit,array(
    			'translator'=>new Translator($_locale.'_'.strtoupper($_locale)),
    			'en' => false,
    			'fr' => false,
    			'de' => true
        		));
    	$editProduitFormDE = self::handleRequestAndSubmit($request,$editProduitFormDE,$produitRepositorty,$produit);
        
        $editProduitFormFR = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale)),
        		'en' => false,
        		'fr' => true,
        		'de' => false
        	));
        $editProduitFormFR = self::handleRequestAndSubmit($request,$editProduitFormFR,$produitRepositorty,$produit);
        
        return $this->render('configuration/produits/editer-produit.html.twig', array(
            'page' => 'editer-produit',
        	'produit' => $produit,
        	'editProduitFormEN' => $editProduitForm->createView(),
        	'editProduitFormDE' => $editProduitFormDE->createView(),
        	'editProduitFormFR' => $editProduitFormFR->createView(),
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

}
