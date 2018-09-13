<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class LivraisonController extends Controller
{
    /**
     * @Route("{_locale}/livraison", name="mas_livraison")
     */
    public function livraisonAction(Request $request,$_locale)
    {
    	$request->setLocale($_locale);
        return $this->render('livraison/livraison.html.twig', array(
            'page'=>'livraison',
            'activehome' => '',
            'activecategorie' => '',
            'activerecettes' => '',
            'activelivraison' => 'active',
            'activecontact' => ''
        ));
    }

}
