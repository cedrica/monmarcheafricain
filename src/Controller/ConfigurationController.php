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
use App\Service\CategoryNode;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\Translator;

class ConfigurationController extends Controller
{

    /**
     * @Route("/{_locale}/configuration/update", name="configuration_controller_update")
     */
	public function adminAction(Request $request, ControllerHelper $helper,$_locale)
    {
        $message = $request->query->get('message');
        $alertType = $request->query->get('alertType');
        $request->query->set('message',null);
        $request->query->set('alertType',null);
        
        $produit = new Produit();
        $createProduitForm = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $createProduitForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $produitRepositorty->findAll();
        

        $recette = new Recette();
        $createRecette = $this->createForm('App\Form\RecetteType', $recette,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $createRecette->handleRequest($request);
        if ($createRecette->isSubmitted() && $createRecette->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
            $image = $recette->getImage();
            $fileName = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $fileName);
            $recette->setImage($fileName);
            $recette->setIngredients(new ArrayCollection());
            $recette->setSteps(new ArrayCollection());
            $em->persist($recette);
            $em->flush();
            return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
                'id' => $recette->getId()
            ));
        }
        
        $recettes = $em->getRepository(Recette::class)->findAll();
        $comptes = $em->getRepository(Compte::class)->findAll();
        return $this->render('configuration/configuration.html.twig', array(
            'page' => 'configuration',
            'message' => $message,
            'alertType' => $alertType,
            'recette' => $createRecette->createView(),
            'produits' => $produits,
            'recettes' => $recettes,
            'comptes' => $comptes
        ));
    }
    
    /**
     * @Route("/{_locale}/configuration", name="configuration_controller_init")
     */
    public function configurationAction(Request $request, ControllerHelper $helper,$_locale)
    {
    	
    	if($request->getSession()->get('compteAdmin') == null){
    		return $this->redirectToRoute('admin_controller_login');
    	}
    	
    	$message = $request->query->get('message');
    	$alertType = $request->query->get('alertType');
    	$produit = new Produit();
    	$categoryNodeList = $helper->convertXmlToObjectList('catalogs/categories.xml');
    	
    	$em = $this->getDoctrine()->getManager();
    	$produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
    	$produits = $produitRepositorty->findAll();
    	$recette = new Recette();
    	$createRecette = $this->createForm('App\Form\RecetteType', $recette,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
    	$createRecette->handleRequest($request);
    	if ($createRecette->isSubmitted() && $createRecette->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		/** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
    		$image = $recette->getImage();
    		$fileName = md5(uniqid()) . '.' . $image->guessExtension();
    		$image->move($this->getParameter('brochures_directory'), $fileName);
    		$recette->setImage($fileName);
    		$recette->setIngredients(new ArrayCollection());
    		$recette->setSteps(new ArrayCollection());
    		$em->persist($recette);
    		$em->flush();
    		return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
    				'id' => $recette->getId()
    		));
    	}
    	
    	$recettes = $em->getRepository(Recette::class)->findAll();
    	$comptes = $em->getRepository(Compte::class)->findAll();
    	
    	$catalogueCategories = $helper->convertXmlToObjectList('catalogs/categories.xml');
    	$arr = array();
    	$arr["--select--"]=-1;
    	
    	foreach ($catalogueCategories as $value) {
    		$arr[$value->getFr()." / ".$value->getEn()." / ".$value->getDe()] =  $value->getId();
    	}
    	
    	$categoryNode = new CategoryNode();
    	$categoryNodeForm = $this->createForm('App\Form\CategoryNodeType', $categoryNode,array('categories'=> $arr));
    	
    	$categoryNodeForm->handleRequest($request);
    	if ($categoryNodeForm->isSubmitted() && $categoryNodeForm->isValid()) {
    		$cCid = count($catalogueCategories)+1;
    		$categoryNode->setId($cCid);
    		$catalogueCategories->add($categoryNode);
    		$helper->addNewObjectToXml('catalogs/categories.xml',$catalogueCategories);
    	}
    	$editProduitForm = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale)),
    			'en' => true,
    			'fr' => false,
    			'de' => false
    	));
    	$editProduitForm = self::handleRequestAndSubmit($request,$editProduitForm,$helper,$produit);
    	return $this->render('configuration/configuration.html.twig', array(
    			'page' => 'configuration',
    			'produit' => $produit,
    			'message' => $message,
    			'alertType' => $alertType,
    			'editProduitForm' => $editProduitForm->createView(),
    			'recette' => $createRecette->createView(),
    			'produits' => $produits,
    			'recettes' => $recettes,
    			'comptes' => $comptes,
    			'categoryNodeList' => $categoryNodeList,
    			'catalogueCategories' => $catalogueCategories,
    			'categoryNodeForm' => $categoryNodeForm->createView()
    	)); 
    }
    public function handleRequestAndSubmit(Request $request,$form,ControllerHelper $helper,$produit){
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    		if ($helper->existeDeja($produit->getNom(), $em)) {
    			return $this->redirectToRoute('configuration_controller_init', array(
    					'produit' => $produit,
    					'page' => 'produit',
    					'message' => 'Désolé un produit avec le nom ' . $produit->getNom() . ' existe déja',
    					'alertType' => 'info',
    					'nom' => $produit->getNom(),
    					'produits' => $produits
    			));
    		}
    		/** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
    		$image = $produit->getImage();
    		$fileName = md5(uniqid()) . '.' . $image->guessExtension();
    		$image->move($this->getParameter('brochures_directory'), $fileName);
    		$produit->setImage($fileName);
    		$dateTime = new \DateTime();
    		$format = 'Y-m-dH:i:s';
    		$formatedDT = $dateTime->format($format);
    		$formatedDT = str_replace("-", "", $formatedDT);
    		$formatedDT = str_replace(":", "", $formatedDT);
    		$produit->setReference($formatedDT);
    		$em->persist($produit);
    		$em->flush();
    		return $this->redirectToRoute('configuration_controller_init', array(
    				'nom' => $produit->getNom(),
    				'page' => 'configuration',
    				'message' => 'Un produit ' . $produit->getNom() . ' a été sauvgardé avec succes',
    				'alertType' => 'succes',
    				'produits' => $produits
    		));
    	}
    	return $form;
    }



    
    
}
