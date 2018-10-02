<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Service\ControllerHelper;
use App\Entity\Login;
use Symfony\Component\Translation\Translator;

class StartController extends Controller
{
    /**
     * @Route("/{_locale}", name="start_controller_start")
     */
	public function startAction(Request $request,  ControllerHelper $helper, $_locale)
    {
    	$session = $request->getSession();
    	$session->set('_locale', $_locale);
    	$request->setLocale($_locale);
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
                    $request->getSession()->set("compte", $compte);
                    return $this->redirectToRoute('profil_controller_open_profil',array('id'=>$compte->getId()));

                } else {
                    return $this->render('connexion/connexion.html.twig', array(
                        'page' => 'connexion',
                        'alertType' => 'error',
                        'message' => 'Mot de pass invalide',
                        'sEnregisterForm' => $sEnregisterForm->createView(),
                        'activehome' => 'active',
                        'activecategorie' => '',
                        'activerecettes' => '',
                        'activelivraison' => '',
                        'activecontact' => ''
                    ));
                }
            } else {
                return $this->render('start/start.html.twig', array(
                    'page' => 'start',
                    'alertType' => 'error',
                    'message' => 'Cette email n´est pas encore inscrite',
                    'sEnregisterForm' => $sEnregisterForm->createView(),
                    'activehome' => 'active',
                    'activecategorie' => '',
                    'activerecettes' => '',
                    'activelivraison' => '',
                    'activecontact' => ''
                ));
            }
        }
        return $this->render('start/start.html.twig', array(
            'page'=>'start',
            'alertType' => null,
            'sEnregisterForm' => $sEnregisterForm->createView(),
            'activehome' => 'active',
            'activecategorie' => '',
            'activerecettes' => '',
            'activelivraison' => '',
            'activecontact' => ''
        ));
    }


}
