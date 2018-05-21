<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class ContactController extends Controller
{
    
    /**
     * @Route("/{_locale}/contact", name="mas_contact")
     */
    public function contactAction()
    {
        return $this->render('contact/contact.html.twig', array(
            'page'=>'contact'
        ));
    }
    
}
