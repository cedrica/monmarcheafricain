<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class StartController extends Controller
{
    /**
     * @Route("/{_locale}", name="start_controller_start")
     */
	public function startAction(Request $request, $_locale)
    {
    	$session = $request->getSession();
    	$session->set('_locale', $_locale);
    	$request->setLocale($_locale);
        return $this->render('start/start.html.twig', array(
            'page'=>'start',
            'activehome' => 'active',
            'activecategorie' => '',
            'activerecettes' => '',
            'activelivraison' => '',
            'activecontact' => ''
        ));
    }
}
