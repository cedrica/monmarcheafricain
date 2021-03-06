<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\Compte;
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
        $translator = new Translator($_locale.'_'.strtoupper($_locale));
        if ($sEnregisterForm->isSubmitted() && $sEnregisterForm->isValid()) {
            $email = $login->getEmail();
            $loginRepository = $this->getDoctrine()->getRepository(Login::class);
            $loginDB = $loginRepository->findOneByEmail($email);
            if($loginDB == null){
            	return $this->redirectToRoute('connexion_controller_connexion', array(
            			'page' => 'connexion',
            			'alertType' => 'error',
            			'message' => $translator->trans('mma.messages.invalidconnectiondata')));
            }
            if ($loginDB != null) {
                if (password_verify($login->getMotDePass(), $loginDB->getMotDePass())) {
                	$compte = $loginDB->getCompte();
                	$compte->setLogin($loginDB);
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
                    		'sEnregisterForm' => $sEnregisterForm->createView(),
                    		'activehome' => '',
                    		'activecategorie' => 'active',
                    		'activerecettes' => '',
                    		'activelivraison' => '',
                    		'activecontact' => ''
                    ));
                }
            } else {
                return $this->render('connexion/connexion.html.twig', array(
                    'page' => 'connexion',
                    'alertType' => 'error',
                    'message' => 'Cette email n´est pas encore inscrite',
                		'sEnregisterForm' => $sEnregisterForm->createView(),
                		'activehome' => '',
                		'activecategorie' => 'active',
                		'activerecettes' => '',
                		'activelivraison' => '',
                		'activecontact' => ''
                ));
            }
        }
        return $this->render('connexion/connexion.html.twig', array(
            'page' => 'connexion',
            'alertType' => null,
            'sEnregisterForm' => $sEnregisterForm->createView(),
            'activehome' => '',
            'activecategorie' => '',
            'activerecettes' => '',
            'activelivraison' => '',
            'activecontact' => ''
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
        return $this->redirectToRoute('connexion_controller_connexion', array(
            'activehome' => '',
            'activecategorie' => '',
            'activerecettes' => '',
            'activelivraison' => '',
            'activecontact' => ''
        ));
    }
    
    /**
     * @Route("{_locale}/mot-de-pass-oublier", name="connection_controller_mot_de_pas_oublie")
     */
    public function motDePassOublierAction(Request $request)
    {
        return $this->render('messages/confirmer-votre-compte.html.twig', array(
            'page' => 'confirmer-votre-compte',
            'alertType' => null
        ));
    }
    
    /**
     * @Route("{_locale}/send-email-for-reseting-password", name="connection_controller_send-email-for-reseting-password")
     */
    public function sendEmailForResetingPasswordAction(Request $request,\Swift_Mailer $mailer, $_locale)
    {
    	$reciever = $request->request->get("email");//reciever
    	$translator = new Translator($_locale.'_'.strtoupper($_locale));
    	$message = (new \Swift_Message('Reset your Password'))
    	->setFrom("info@Monmarcheafricain.com")
    	->setTo($reciever)
    	->setBody(
    			$this->renderView(
    					// templates/emails/registration.html.twig
    					'emails/reset-password.html.twig',
    					array('email' => $reciever)
    					),
    			'text/html'
    			);
    	$mailer->send($message);
    	return $this->render('/messages/we-send-you-an-email.html.twig',
    			array(
    					'page'=>'we-send-you-an-email',
    					'message' => $translator->trans('mma.messages.wesendyouanemail')
    			));
    	
 
    }
    
    /**
     * @Route("{_locale}/enter-nouvelles-donnees", name="connection_controller_enter-nouvelles-donnees")
     */
    public function entrerLesNouvellesDonneesChangerAction(Request $request)
    {
    	$email = $request->get('email');
    	return $this->render('/messages/we-send-you-an-email.html.twig',
    			array(
    					'page'=>'we-send-you-an-email',
    					'message' => 'dd	'
    			));
    }
    /**
     * @Route("{_locale}/changer-mot-de-passe/{email}", name="connection_controller_changer_mot_de_passe")
     */
    public function motDePasseChangerAction(Request $request,$email)
    {
    	
    	$em = $this->getDoctrine()->getManager();
    	$motDePasse = $request->request->get('motDePasse');
    	$confirmerMotDePasse = $request->request->get('confirmerMotDePasse');
    	$translator = new Translator($_locale.'_'.strtoupper($_locale));
    	if (strcmp($confirmerMotDePasse, $motDePasse) == 0) {
    		$loginRepository = $em->getRepository(Login::class);
    		$login = $loginRepository->findOneByEmail($email);
    		$compte =  $login->getCompte();
    		
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
    			return $this->render('commun/error-info-success.html.twig', array(
    					'page' => 'error-info-success',
    					'alertType' => 'success',
    					'message' => $translator->trans('mma.messages.passwordsuccessfulychanged')
    			));
    		}else{
    			return $this->redirectToRoute('connexion_controller_connexion');
    		}
    	}else {
    		return $this->render('messages/creer-mot-de-pass-successfull.html.twig', array(
    				'page' => 'connexion',
    				'alertType' => 'error',
    				'message' => 'Mot de pass invalide'
    		));
    	}
    }
}
