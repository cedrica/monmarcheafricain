<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LangueController extends Controller
{

    /**
     * @Route("/", name="langue_controller_set_default_language")
     */
    public function configureLangueAction(Request $request)
    {
        $request->setLocale('en');
        $request->setDefaultLocale('en');
       return $this->redirectToRoute('start_controller_start',array('_locale'=>'en'));
        
    }
    

}
