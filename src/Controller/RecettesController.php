<?php
namespace App\Controller;

use App\Entity\Recette;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Recette controller.
 */
class RecettesController extends Controller
{

    /**
     * @Route("/{_locale}/quelque-recettes",name="mas_recettes")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $recettes = $em->getRepository(Recette::class)->findAll();
        return $this->render('quelque-recettes/quelque-recettes.html.twig', array(
            'page' => 'recettes',
            'recettes' => $recettes
        ));
    }



    /**
     * @Route("{_locale}/quelque-recettes/{id}", name="mas_recette")
     */
    public function findRecetteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $recette = $em->getRepository(Recette::class)->find($id);
        return $this->render('quelque-recettes/recette.html.twig', array(
            'page' => 'recette',
            'recette' => $recette
        ));
    }
}
