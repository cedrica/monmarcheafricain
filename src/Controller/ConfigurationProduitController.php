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
    	$ancienneImage = $produit->getImage();
    	$editProduitForm = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $editProduitForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($editProduitForm->isSubmitted() && $editProduitForm->isValid()) {
        	$fs = new FileSystem();
        	$fs->remove($this->getParameter('brochures_directory').'/'.$ancienneImage);
        	/** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
        	$image = $produit->getImage();
        	$fileName = md5(uniqid()) . '.' . $image->guessExtension();
        	$image->move($this->getParameter('brochures_directory'), $fileName);
        	$produit->setImage($fileName);
        	$produit->setActif(true);
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
        return $this->render('configuration/produits/editer-produit.html.twig', array(
            'page' => 'editer-produit',
        	'editProduitForm' => $editProduitForm->createView(),
        ));
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
