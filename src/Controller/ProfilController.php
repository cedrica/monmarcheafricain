<?php
namespace App\Controller;

use App\Entity\Compte;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Adresse;
use App\Entity\Login;

/**
 * Profil controller.
 */
class ProfilController extends Controller
{

    /**
     * @Route("{_locale}/profil/{id}",name="profil_controller_open_profil")
     *
     * @Method({"GET", "POST"})
     */
    public function openProfilAction(Request $request, $id)
    {
        
        $em = $this->getDoctrine()->getManager();
        /**
         * 
         * @var Compte $compte
         */
        $compte = $em->getRepository(Compte::class)->find($id);
        if($compte == null){
        	return $this->redirectToRoute('start_controller_start');
        }
        $editerDonneesPersoForm = $this->createForm('App\Form\EditerDonneesPersoType', $compte);
        $editerDonneesPersoForm->handleRequest($request);
        if ($editerDonneesPersoForm->isSubmitted() && $editerDonneesPersoForm->isValid()) {
           
            $em->persist($compte);
            $em->flush();
            return $this->redirectToRoute('profil_controller_open_profil', array(
                "id" => $compte->getId()
            ));
        }else{
        }
        
        /**
         * 
         * @var Login $login
         */
        $login = $compte->getLogin();
        $editerLoginForm = $this->createForm('App\Form\EditerLoginType', $login);
        $editerLoginForm->handleRequest($request);
        if ($editerLoginForm->isSubmitted() && $editerLoginForm->isValid()) {
            $encrypted_password = password_hash($login->getMotDePass(), PASSWORD_DEFAULT);
            $login->setMotDePass($encrypted_password);
            $compte->setLogin($login);
            $em->persist($compte);
            $em->flush();
            return $this->redirectToRoute('profil_controller_open_profil', array(
                "id" => $compte->getId()
            ));
        }
        
        /**
         *
         * @var Adresse $adresse
         */
        $adresse =  new Adresse();
        $editerAdresseForm = $this->createForm('App\Form\EditerAdressesType', $adresse);
        $editerAdresseForm->handleRequest($request);
        if ($editerAdresseForm->isSubmitted() && $editerAdresseForm->isValid()) {
            return $this->redirectToRoute('profil_controller_open_profil', array(
                "id" => $compte->getId()
            ));
        }
        return $this->render('profil/profil.html.twig', array(
            'page' => 'profil',
            'compte' => $compte,
            'editerLoginForm'=>$editerLoginForm->createView(),
            'editerDonneesPersoForm'=>$editerDonneesPersoForm->createView(),
            'editerAdresseForm' => $editerAdresseForm->createView()
        ));
    }
    
    
    /**
     * @Route("{_locale}/profil/creer-adresse/{compte_id}", name="profil_controller_creer_adresse")
     */
    public function creerAdresseAction(Request $request,$compte_id)
    {
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            
            /**
             * 
             * @var Compte $compte
             */
            $compte = $em->getRepository(Compte::class)->find($compte_id);
            
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
            $adresse->setCompte($compte);
            
            $em->persist($adresse);
            $em->flush();
        }
        return $this->redirectToRoute('profil_controller_open_profil', array(
            'id' => $compte_id
        ));
    }
    
    
    /**
     * @Route("{_locale}/profil/editer-adresse/{id}", name="profil_controller_editer_adresse")
     */
    public function editerAdresseAction(Request $request,$id)
    {
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            
            /**
             *
             * @var Adresse $adresse
             */
            $adresse = $em->getRepository(Adresse::class)->find($id);
            
            
            // ADRESSE
            $pays = $request->request->get('pays');
            $ville = $request->request->get('ville');
            $boitePostale = $request->request->get('boitePostale');
            $rueEtNr = $request->request->get('rueEtNr');
            
            $adresse->setBoitePostale($boitePostale);
            $adresse->setVille($ville);
            $adresse->setPays($pays);
            $adresse->setRueEtNr($rueEtNr);
            
            $em->persist($adresse);
            $em->flush();
        }
        return $this->redirectToRoute('profil_controller_open_profil', array(
            'id' => $adresse->getCompte()->getId()
        ));
    }
    
    /**
     * @Route("{_locale}/profil/delete-adresse/{id}", name="profil_controller_delete_adresse")
     */
    public function deleteAdresseAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        
        /**
         *
         * @var Adresse $adresse
         */
        $adresse = $em->getRepository(Adresse::class)->find($id);
        if($adresse != null){
            $em->remove($adresse);
            $em->flush();
        }
        return $this->redirectToRoute('profil_controller_open_profil', array(
            'id' => $adresse->getCompte()->getId()
        ));
    }
    
}
