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

        return $this->render('configuration/produits/editer-produit.html.twig', array(
            'page' => 'editer-produit',
            'produit' => $produit,
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
     * @Route("/{_locale}/edit-product", name="configuration_produit_controller_save_edited_product")
     */
    public function saveEditedProduct(Request $request)
    {
        if($request->isMethod('POST')){

            $nom = $request->request->get('nomFR');
            $prix = $request->request->get('prix');
            $price = $request->request->get('price');
            $preis = $request->request->get('preis');
            $etat = $request->request->get('etat');
            $name = $request->request->get('name');
            $nameDE = $request->request->get('nameDE');
            $action = $request->request->get('action');
            $actif = $request->request->get('actif');
            $quantite = $request->request->get('quantite');
            $category = $request->request->get('category');
            $categorie = $request->request->get('categorie');
            $kategorie = $request->request->get('kategorie');
            $disponible = $request->request->get('disponible');
            $pourcentageDeRabait = $request->request->get('pourcentageDeRabait');
            $actionDebut = $request->request->get('actionDebut');
            $actionFin = $request->request->get('actionFin');
            $descriptionFR = $request->request->get('descriptionFR');
            $descriptionEN = $request->request->get('descriptionEN');
            $descriptionDE = $request->request->get('descriptionDE');
            $fileName = $_FILES['image']['name'];
            $bfileName = basename($fileName);
            $tmpFile = $_FILES['image']['tmp_name'];
            move_uploaded_file($tmpFile,"uploads/brochures/$bfileName");

            $id = $request->query->get('id');
            $em = $this->getDoctrine()->getManager();
            $produit = $em->getRepository(Produit::class)->find($id);
            $produit->setNom($nom);
            $produit->setName($name);
            $produit->setNameDE($nameDE);
            $produit->setCategorie($categorie);
            $produit->setCategory($category);
            $produit->setKategorie($kategorie);
            $produit->setPrix($prix);
            $produit->setPreis($preis);
            $produit->setPrice($price);
            $produit->setEtat($etat);
            $produit->setQuantite($quantite);
            $produit->setDisponible($disponible == 'NULL'? false:true);
            $produit->setImage($fileName);
            $produit->setActif( $actif == 'NULL'? false:true);
            $produit->setAction($action);
            $produit->setPourcentageDeRabait($pourcentageDeRabait);
            $produit->setActionDebut($actionDebut);
            $produit->setActionFin($actionFin);
            $produit->setDescriptionFR($descriptionFR);
            $produit->setDescriptionEN($descriptionEN);
            $produit->setDescriptionDE($descriptionDE);

            $em->persist($produit);
            $em->flush();

            $em->flush();

        }
        return $this->redirectToRoute('admin_controller_configuration',
            array(
                '_locale'=>$request->getLocale(),
                'alertType' => 'succes',
                'message' => 'Produit editer avec succes'
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
            $nom = $request->request->get('nomFR');
            $prix = $request->request->get('prix');
            $etat = $request->request->get('etat');
            $name = $request->request->get('name');
            $nameDE = $request->request->get('nameDE');
            $action = $request->request->get('action');
            $actif = $request->request->get('actif');
            $quantite = $request->request->get('quantite');
            $category = $request->request->get('category');
            $categorie = $request->request->get('categorie');
            $kategorie = $request->request->get('kategorie');
            $disponible = $request->request->get('disponible');
            $pourcentageDeRabait = $request->request->get('pourcentageDeRabait');
            $actionDebut = $request->request->get('actionDebut');
            $actionFin = $request->request->get('actionFin');
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
            $dateTime = new \DateTime();
            $format = 'Y-m-dH:i:s';
            $formatedDT = $dateTime->format($format);
            $formatedDT = str_replace("-", "", $formatedDT);
            $formatedDT = str_replace(":", "", $formatedDT);

            $produit->setNom($nom);
            $produit->setName($name);
            $produit->setNameDE($nameDE);
            $produit->setCategorie($categorie);
            $produit->setCategory($category);
            $produit->setKategorie($kategorie);
            $produit->setPrix($prix);
            $produit->setEtat($etat);
            $produit->setQuantite($quantite);
            var_dump($disponible);
            var_dump($actif);
            var_dump($etat);
            $produit->setDisponible($disponible == 'NULL'? false:true);
            $produit->setImage($fileName);
            $produit->setActif( $actif == 'NULL'? false:true);
            $produit->setAction($action);
            $produit->setPourcentageDeRabait($pourcentageDeRabait);
            $produit->setActionDebut($actionDebut);
            $produit->setActionFin($actionFin);
            $produit->setDescriptionFR($descriptionFR);
            $produit->setDescriptionEN($descriptionEN);
            $produit->setDescriptionDE($descriptionDE);
            $produit->setReference($formatedDT);

            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('admin_controller_configuration',
                array(
                    '_locale'=>$request->getLocale(),
                    'alertType' => 'succes',
                    'message' => 'Produit editer avec succes'
                ));
        }
        return  $this->redirectToRoute('admin_controller_configuration');
    }

}
