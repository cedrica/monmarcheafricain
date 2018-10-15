<?php
namespace App\Util;

use Symfony\Component\HttpFoundation\Request;

class MailUtil 
{
	public function sendInvoicePerMailTo(Request $request, \Swift_Mailer $mailer,$reciever, $emailContent)
	{
		$sender = 'info@Monmarcheafricain.com';
		$message = (new \Swift_Message('Un client vous a laisser un méssage'))
		->setFrom($sender)
		->setTo($reciever)
		->setBody(
				$emailContent,
				'text/html'
				);
		
		$mailer->send($message);

	}    
}

?>