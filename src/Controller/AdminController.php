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
    		$sEnregisterForm = $this->createForm('App\Form\SEnregistrerType', 
    				$login,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale)),'admin-login' => true));
    		$sEnregisterForm->handleRequest($request);
    		if ($sEnregisterForm->isSubmitted() && $sEnregisterForm->isValid()) {
    			$email = $login->getEmail();
    			$loginDB = $this->getDoctrine()->getRepository(Login::class)->findOneByEmail($email);
    			if($loginDB == null){
    				return $this->render('admin/login.html.twig', array(
    						'page' => 'login',
    						'alertType' => 'error',
    						'message' => 'Cette email n´est pas encore inscrite',
    						'sEnregisterForm' => $sEnregisterForm->createView()
    				));
    			} else {
    				$compteRepository = $this->getDoctrine()->getRepository(Compte::class);
    				$compteAdmin = $compteRepository->findOneBy(['login_id' => $loginDB->getId()]);
    				return $this->render('configuration/conf.html.twig');
    				if (password_verify($login->getMotDePass(), $loginDB->getMotDePass()) && strcmp($compteAdmin->getRole(), "ADMIN") == 0) {
    					$request->getSession()->set('compteAdmin',$compteAdmin) ;
    					return $this->redirectToRoute('configuration_controller_init', array(
    							'page' => 'configuration',
    							'message' =>null,
    							'alertType' => null
    					));
    				}
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
