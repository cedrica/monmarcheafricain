<?php

namespace App\Controller;


use App\Entity\Login;
use App\Entity\Produit;
use App\Service\CategoryNode;
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

class AdminController extends Controller
{
    /**
     * @Route("/{_locale}/admin", name="admin_controller_login")
     */
	public function loginAction(Request $request, ControllerHelper $helper,$_locale)
    {
            $translator = new Translator($_locale);
    		$login = new Login();
    		$sEnregisterForm = $this->createForm('App\Form\SEnregistrerType', $login,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale)),
    																						'admin-login' => true
    		));
    		$sEnregisterForm->handleRequest($request);
    		if ($sEnregisterForm->isSubmitted() && $sEnregisterForm->isValid()) {
    			$email = $login->getEmail();
    			$em = $this->getDoctrine()->getManager();
    			$compteAdmin = $helper->trouveLeCompteByEmail($email, $em);
    			if($compteAdmin == null){
    				return $this->redirectToRoute('admin_controller_login', array(
    				    'message' => $translator->trans('mma.messages.accountnotavailable')
                    ));
    			}
    			$loginDB = $compteAdmin->getLogin();
    			if ($loginDB != null) {
    				if (password_verify($login->getMotDePass(), $loginDB->getMotDePass()) && strcmp($compteAdmin->getRole(), "ADMIN") == 0) {
    					$request->getSession()->set('compteAdmin',$compteAdmin) ;
    					return $this->redirectToRoute('admin_controller_configuration', array(
    							'page' => 'configuration',
    							'message' =>null,
    							'alertType' => null
    					));
    				}
    			} else {
    				return $this->render('admin/login.html.twig', array(
    						'page' => 'login',
    						'alertType' => 'error',
    						'message' => 'Cette email n´est pas encore inscrite',
    						'sEnregisterForm' => $sEnregisterForm->createView()
    				));
    			}
    		}
    		return $this->render('admin/login.html.twig', array(
    				'page' => 'login',
    				'alertType' => null,
                    'message' => '',
    				'sEnregisterForm' => $sEnregisterForm->createView()
    		));
     }
    
     /**
      * @Route("/{_locale}/configuration", name="admin_controller_configuration")
      */
     public function configurationAction(Request $request, ControllerHelper $helper,$_locale)
     {
     	if($request->getSession()->get('compteAdmin') == null){
     		return $this->redirectToRoute('admin_controller_login');
     	}
     	$message = $request->query->get('message');
     	$alertType = $request->query->get('alertType');
     	
     	$categoryNodeList = $helper->convertXmlToObject('catalogs/categories.xml');
     	
     	$produit = new Produit();
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
     	
     	$editProduitForm = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale)),
     			'en' => true,
     			'fr' => false,
     			'de' => false
     	));
     	
     	$catalogueCategories = $helper->convertXmlToObject('catalogs/categories.xml');  
     	$arr = array();
     	foreach ($catalogueCategories as $value) {
     		$arr[$value->getFr()." / ".$value->getEn()." / ".$value->getDe()] =  $value->getId();
     	}
     	$categoryNode = new CategoryNode();
     	$categoryNodeForm = $this->createForm('App\Form\CategoryNodeType', $categoryNode,array('categories'=> $arr));
     	$categoryNodeForm->handleRequest($request);
     	if ($categoryNodeForm->isSubmitted() && $categoryNodeForm->isValid()) {
     		$cCid = count($catalogueCategories)+1;
     		$categoryNode->setId($cCid);
     		$helper->addNewObjectToXml('catalogs/categories.xml',$categoryNode);
     		//return $this->redirectToRoute('admin_controller_configuration');
     	}
     	
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
	     		return $this->redirectToRoute('configuration_controller_init_view', array(
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
	     	// This way to remove file work
	     	// $fs = new Filesystem();
	     	// $fs->remove("uploads/ebff62e127db54f09058ac980d029609.png");
	     	// ///
	     	$produit->setImage($fileName);
	     	$dateTime = new \DateTime();
	     	$format = 'Y-m-dH:i:s';
	     	$formatedDT = $dateTime->format($format);
	     	$formatedDT = str_replace("-", "", $formatedDT);
	     	$formatedDT = str_replace(":", "", $formatedDT);
	     	$produit->setReference($formatedDT);
	     	$em->persist($produit);
	     	$em->flush();
	     	return $this->redirectToRoute('configuration_controller_init_view', array(
	     			'nom' => $produit->getNom(),
	     			'page' => 'configuration',
	     			'message' => 'Un produit ' . $produit->getNom() . ' a été sauvgardé avec succes',
	     			'alertType' => 'succes',
	     			'produits' => $produits
	     	));
     	}
     	return $form;
     }
     /**
      * @Route("/{_locale}/logout", name="admin_controller_logout")
      */
     public function logoutAction(Request $request)
     {
     	$request->getSession()->set('compteAdmin',null) ;
     	return $this->redirectToRoute('admin_controller_login');
     }
     /**
      * @Route("/{_locale}/configuration/{id}", name="admin_controller_toggle_active_prod")
      */
     public function toggleActiveProdAction(Request $request, $id)
     {
     	$em = $this->getDoctrine()->getManager();
     	$produit = $em->getRepository(Produit::class)->find($id);
     	if ($produit != null) {
     		$produit->setActif(! $produit->getActif());
     		$em->persist($produit);
     		$em->flush();
     	}
     	$produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
     	$produits = $produitRepositorty->findAll();
     	return $this->render('configuration/configuration.html.twig', array(
     			'page' => 'configuration',
     			'produits' => $produits
     			
     	));
     }
    
}
