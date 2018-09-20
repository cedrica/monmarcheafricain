<?php

namespace App\Controller;

use App\Service\ControllerHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Compte;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\Translator;

class ConfigurationClientController extends Controller
{

    /**
     * @Route("/{_locale}/edit-role/{id}", name="configuration_client_controller_editrole")
     */
    public function editRoleAction(Request $request, $id, $_locale)
    {
    	$translator = new Translator($_locale.'_'.strtoupper($_locale));
    	$em = $this->getDoctrine()->getManager();
        $compteRepositorty = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $compteRepositorty->find($id);
        $newRole = $request->request->get('role');
        $compte->setRole($newRole);
        $em->flush();
        $message = $translator->trans('mma.clientcatalog.roleediter');
        $alertType = 'success';
        return $this->redirectToRoute('configuration_controller_init_view', array(
        		'message' => $message,
        		'alertType' => $alertType 
        ));
    }


   

}
