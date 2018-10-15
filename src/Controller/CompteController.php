<?php
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Adresse;
use App\Entity\Compte;
use App\Entity\Login;
use App\Service\ControllerHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Translation\Translator;

class CompteController extends Controller
{

    /**
     * @Route("/{_locale}/creer-compte", name="compte_controller_creercompte")
     */
	public function creerCompteAction(Request $request, $_locale)
    {
    	
    	$alertType = $request->request->get('alertType');
    	$message = $request->request->get('message');
    	$email = $request->request->get('email');
        if ($request->getMethod() == 'POST') {
            $email = $request->request->get('email');
        }
        return $this->render('connexion/creer-compte/creer-compte.html.twig', array(
            'email' => $email,
            'page' => 'creerCompte',
        	'message' => $message,
        	'alertType' => $alertType,
            'activehome' => '',
            'activecategorie' => '',
            'activerecettes' => '',
            'activelivraison' => '',
            'activecontact' => ''
        ));
    }

    /**
     * @Route("/{_locale}/ouverture-de-compte-reussi", name="compte_controller_creercomptereussi")
     */
    public function ouvertureDeCompteReussiAction(Request $request, ControllerHelper $helper, $_locale)
    {
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            // LOGIN
            $email = $request->request->get('email');
            $motDePasse = $request->request->get('motDePasse');
            $confirmerMotDePasse = $request->request->get('confirmerMotDePasse');
            $translator = new Translator($_locale.'_'.strtoupper($_locale));
            if (strcmp($motDePasse, $confirmerMotDePasse) != 0) {
            	return $this->redirectToRoute('compte_controller_creercompte', array(
                    'email' => $email,
            		'message' => $translator->trans('mma.messages.passwordmissmatch'),
                    'alertType' => 'error'
                ));
            }
            
            if ($helper->emailExisteDeja($email, $em)) {
                
            	return $this->redirectToRoute('compte_controller_creercompte', array(
                    'email' => $email,
                    'message' => 'Cette email est deja enrégistré',
                    'alertType' => 'error'
                ));
            }
            $login = new Login();
            $login->setEmail($email);
            $encrypted_password = password_hash($motDePasse, PASSWORD_DEFAULT);
            $login->setMotDePass($encrypted_password);
            $em->persist($login);
            $em->flush();
            // ADRESSE
            $pays = $request->request->get('pays');
            $ville = $request->request->get('ville');
            $boitePostale = $request->request->get('boitePostale');
            $rueEtNr = $request->request->get('rueEtNr');
            
            $adresse = new Adresse();
            $adresse->setBoitePostale($boitePostale);
            $adresse->setVille($ville);
            $adresse->setPays($pays);
            $adresse->setRueEtNr($rueEtNr);
            
            // DONNEE PERSO
            $montitre = $request->request->get('montitre');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $dateDeNaissance = $request->request->get('dateDeNaissance');
            $role = "SIMPLE_CLIENT";
            
            $compte = new Compte();
            $compte->setTitre($montitre);
            
            $compte->setDateDeNaissance(new \DateTime($dateDeNaissance));
            $compte->setNom($nom);
            $compte->setPrenom($prenom);
            $compte->setLogin($login);
            $compte->setRole($role);
            
            $em->persist($compte);
            $em->flush();
            
            $login->setCompte($compte);
            $em->persist($login);
            $em->flush();
            
            $adresse->setCompte($compte);
            $em->persist($adresse);
            $em->flush();
        }
        return $this->render('messages/ouverture-de-compte.-reussi.html.twig', array(
            'page' => 'ouvertureDeCompteReussi'
        ));
    }

    
    /**
     * @Route("/{_locale}/delete-compte/{id}", name="compte_controller_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteCompteAction(Request $request,$id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$compte = $em->getRepository(Compte::class)->find($id);
    	$em->remove($compte);
    	$em->flush();
    	$request->request->set('alertType',null);
    	$request->request->set('message',null);
    	return $this->redirectToRoute('configuration_controller_init', 
    			array(
    			'cfg' => 'cli',
    			'_locale'=>$request->getLocale(),
    			'alertType' => 'succes',
    			'message' => 'Client enlevée avec succes'
    	));
    }
    
    /**
     * @Route("/{_locale}/editer-compte/{id}", name="compte_controller_edit")
     * @Method({"GET", "POST"})
     */
    public function editerCompteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $compte = $em->getRepository(Compte::class)->find($id);
        return $this->render('configuration/clients/editer-client.html.twig', array(
            'page' => 'editerClient',
            'compte' => $compte,
            'alertType' => null
        ));
    }

    /**
     * @Route("/{_locale}/changer-role/{id}", name="compte_controller_changer_role")
     */
    public function changerRoleAction(Request $request, $id)
    {
        $role = $request->request->get('role');
        $em = $this->getDoctrine()->getManager();
        $compte = $em->getRepository(Compte::class)->find($id);
        $compte->setRole($role);
        $em->persist($compte);
        $em->flush();
        return $this->redirectToRoute('compte_controller_edit_compte', array(
            'id' => $compte->getId()
        ));
    }

    //Use this function for aja call
    /**
     * @Route("/{_locale}/filtrer-compte/ajax", name="compte_controller_filtrer_compte")
     */
    public function filtrerCompteAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $search = $request->get('search');
            $jsonData = array();
            if(empty(trim($search))){
                return new JsonResponse($jsonData);
            }
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery('SELECT c
                              FROM App:Compte c
                              WHERE c.nom like :search')->setParameter('search', $search.'%');
            $comptes = $query->getResult();
            $idx = 0;
            foreach ($comptes as $compte) {
                $temp = array(
                    'id' => $compte->getId(),
                    'titre' => $compte->getTitre(),
                    'nom' => $compte->getNom(),
                    'prenom' => $compte->getPrenom(),
                    'role' => $compte->getRole()
                );
                $jsonData[$idx ++] = $temp;
            }
            $response = new JsonResponse($jsonData);
            return $response;
        } else {
            return $this->render('clients/editer-client.html.twig', array(
                'page' => 'editerClient',
                'compte' => null,
                'alertType' => null
            ));
        }
    }
}
