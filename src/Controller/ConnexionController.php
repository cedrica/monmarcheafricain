<?php
namespace App\Controller;

use App\Entity\Login;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Service\ControllerHelper;
use Symfony\Component\Translation\Translator;

class ConnexionController extends Controller
{

    /**
     * @Route("{_locale}/connexion", name="connexion_controller_connexion")
     */
	public function connexionAction(Request $request, ControllerHelper $helper,$_locale)
    {
        $login = new Login();
        $sEnregisterForm = $this->createForm('App\Form\SEnregistrerType', $login,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $sEnregisterForm->handleRequest($request);
        if ($sEnregisterForm->isSubmitted() && $sEnregisterForm->isValid()) {
            $email = $login->getEmail();
            $em = $this->getDoctrine()->getManager();
            $compte = $helper->trouveLeCompteByEmail($email, $em);
            if($compte == null){
                return $this->redirectToRoute('confirm_controller_donnees_de_connexion_invalide');
            }
            $loginDB = $compte->getLogin();
            if ($loginDB != null) {
                if (password_verify($login->getMotDePass(), $loginDB->getMotDePass())) {
                    $allerAlaCaisse = $request->getSession()->get('allerALaCaisse');
                    $request->getSession()->set("compte", $compte);
                    if ($allerAlaCaisse != null && $allerAlaCaisse == true) {
                        return $this->redirectToRoute('panier_controller_verifier_connexion');
                    } else {
                        return $this->redirectToRoute('profil_controller_open_profil',array('id'=>$compte->getId()));
                    }
                } else {
                    return $this->render('connexion/connexion.html.twig', array(
                        'page' => 'connexion',
                        'alertType' => 'error',
                        'message' => 'Mot de pass invalide',
                        'sEnregisterForm' => $sEnregisterForm->createView()
                    ));
                }
            } else {
                return $this->render('connexion/connexion.html.twig', array(
                    'page' => 'connexion',
                    'alertType' => 'error',
                    'message' => 'Cette email n´est pas encore inscrite',
                    'sEnregisterForm' => $sEnregisterForm->createView()
                ));
            }
        }
        return $this->render('connexion/connexion.html.twig', array(
            'page' => 'connexion',
            'alertType' => null,
            'sEnregisterForm' => $sEnregisterForm->createView()
        ));
    }


    /**
     * @Route("{_locale}/deconnexion", name="connection_controller_deconnexion")
     */
    public function deconnexionAction(Request $request)
    {
        $request->getSession()->set('allerALaCaisse', false);
        $request->getSession()->set('compte', null);
        $request->getSession()->set('deliveryAdress', null);
        $request->getSession()->set('deliveryWay', null);
        return $this->redirectToRoute('connexion_controller_connexion');
    }
    
    /**
     * @Route("{_locale}/mot-de-pass-oublier", name="connection_controller_mot_de_pas_oublie")
     */
    public function motDePassOublierAction(Request $request)
    {
        return $this->render('connexion/creer-mot-de-pass.html.twig', array(
            'page' => 'creer-mot-de-pass',
            'alertType' => null
        ));
    }
    
    /**
     * @Route("{_locale}/creer-un-nouveau-mot-de-pass", name="connection_controller_creer_mot_de_pass")
     */
    public function creerNouveauMotDePassAction(Request $request, ControllerHelper $helper)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $request->request->get('email');
        $motDePass = $request->request->get('motDePasse'); 
        $confirmerMotDePasse = $request->request->get('confirmerMotDePasse'); 
        if (strcmp($confirmerMotDePasse, $motDePass) == 0) {
            /**
             * 
             * @var \App\Entity\Compte $compte
             */
           $compte =  $helper->trouveLeCompteByEmail($email, $em);
          
           /**
            * 
            * @var \App\Entity\Login $login
            */
           if($compte != null){ 
               $login = $compte->getLogin();
               $encrypted_password = password_hash($motDePass, PASSWORD_DEFAULT);
               $login->setMotDePass($encrypted_password);
               $em->persist($login);
               $em->flush();
               return $this->render('connexion/creer-mot-de-pass-successfull.html.twig', array(
                   'page' => 'creer-mot-de-passe-success',
                   'alertType' => 'error',
                   'message' => 'Mot de pass invalide'
               ));
           }else{
               return $this->redirectToRoute('connexion_controller_connexion');
           }
        }else {
            return $this->render('connexion/creer-mot-de-pass-successfull.html.twig', array(
                'page' => 'connexion',
                'alertType' => 'error',
                'message' => 'Mot de pass invalide'
            ));
        }
    }
}
