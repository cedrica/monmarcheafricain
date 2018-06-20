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
        
        if ($createProduitForm->isSubmitted() && $createProduitForm->isValid()) {
            if ($helper->existeDeja($produit->getNom(), $em)) {
                return $this->redirectToRoute('configuration_controller_init_view', array(
                    'produit' => $produit,
                    'page' => 'produit',
                    'message' => 'Désolé un produit avec le nom ' . $produit->getNom() . ' existe déja',
                    'alertType' => 'info',
                    'nom' => $produit->getNom(),
                    'produits' => $produits
                ));
            }
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
            $image = $produit->getImage();
            $fileName = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $fileName);
            // This way to remove file work
            // $fs = new Filesystem();
            // $fs->remove("uploads/ebff62e127db54f09058ac980d029609.png");
            // ///
            $produit->setImage($fileName);
            $dateTime = new \DateTime();
            $format = 'Y-m-dH:i:s';
            $formatedDT = $dateTime->format($format);
            $formatedDT = str_replace("-", "", $formatedDT);
            $formatedDT = str_replace(":", "", $formatedDT);
            $produit->setReference($formatedDT);
            $produit->setActif(true);
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('configuration_controller_init_view', array(
                'nom' => $produit->getNom(),
                'page' => 'configuration',
                'message' => 'Un produit ' . $produit->getNom() . ' a été sauvgardé avec succes',
                'alertType' => 'succes',
                'produits' => $produits
            ));
        }
        
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
            'produitForm' => $createProduitForm->createView(),
            'recette' => $createRecette->createView(),
            'produits' => $produits,
            'recettes' => $recettes,
            'comptes' => $comptes
        ));
    }



    /**
     * @Route("/{_locale}/configuration/{id}", name="configuration_controller_toggle_active_prod")
     */
    public function toggleActiveProdAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);
        if ($produit != null) {
            $produit->setActif(! $produit->getActif());
            $em->persist($produit);
            $em->flush();
        }
        $produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $produitRepositorty->findAll();
        return $this->render('configuration/configuration.html.twig', array(
            'page' => 'configuration',
            'produits' => $produits
        
        ));
    }
    
    
}
