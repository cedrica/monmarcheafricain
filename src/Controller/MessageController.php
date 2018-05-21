<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller
{
 

    
    /**
     * @Route("/{_locale}/message-envoye", name="mas_msgenvoye")
     */
    public function messageEnvoyeAction(Request $request, \Swift_Mailer $mailer,$_locale)
    {
    	$request->setLocale($_locale);
        $reciever = $request->request->get('reciever');
        $sender = $request->request->get('sender');
        $msgbody = $request->request->get('msgbody');
        $message = (new \Swift_Message('Un client vous a laisser un méssage'))
        ->setFrom($sender)
        ->setTo($reciever)
        ->setBody($msgbody,
            'text/html'
            );
        
        $mailer->send($message);
        return $this->render('/contact/message-envoye/message-envoye.html.twig',
            array(
                'page'=>'message-envoye'
            ));
    }
    

}
