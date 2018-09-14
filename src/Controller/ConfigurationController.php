<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Recette;
use App\Service\ControllerHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Compte;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\Translator;

class ConfigurationController extends Controller
{

    /**
     * @Route("/{_locale}/configuration", name="configuration_controller_init_view")
     */
	public function adminAction(Request $request, ControllerHelper $helper,$_locale)
    {
        $message = $request->query->get('message');
        $alertType = $request->query->get('alertType');
        $produit = new Produit();
        $createProduitForm = $this->createForm('App\Form\ProduitType', $produit,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $createProduitForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $produitRepositorty->findAll();
        

        $recette = new Recette();
        $createRecette = $this->createForm('App\Form\RecetteType', $recette,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $createRecette->handleRequest($request);
        if ($createRecette->isSubmitted() && $createRecette->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
            $image = $recette->getImage();
            $fileName = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $fileName);
            $recette->setImage($fileName);
            $recette->setIngredients(new ArrayCollection());
            $recette->setSteps(new ArrayCollection());
            $em->persist($recette);
            $em->flush();
            return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
                'id' => $recette->getId()
            ));
        }
        
        $recettes = $em->getRepository(Recette::class)->findAll();
        $comptes = $em->getRepository(Compte::class)->findAll();
        return $this->render('configuration/configuration.html.twig', array(
            'page' => 'configuration',
            'message' => $message,
            'alertType' => $alertType,
            'recette' => $createRecette->createView(),
            'produits' => $produits,
            'recettes' => $recettes,
            'comptes' => $comptes
        ));
    }




    
    
}
