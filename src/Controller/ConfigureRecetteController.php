<?php
namespace App\Controller;

use App\Entity\Recette;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Ingredient;
use App\Entity\Step;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\Translator;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Configure Recette controller.
 */
class ConfigureRecetteController extends Controller
{

    /**
     * @Route("/{_locale}/configurer-la-recette/{id}", name="configure_recette_controller_configure_recette")
     */
    public function configureReceteAction(Request $request, $id,$_locale)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         *
         * @var \App\Entity\Recette $recette
         */
        $recette = $em->getRepository(Recette::class)->find($id);
        $ancienneImage = $recette->getImage();
        $editerRecetteForm = $this->createForm('App\Form\RecetteType', $recette,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $editerRecetteForm->handleRequest($request);
        if ($editerRecetteForm->isSubmitted() && $editerRecetteForm->isValid()) {
        	/** @var Symfony\Component\HttpFoundation\File\UploadedFile $image */
        	$image = $recette->getImage();
        	$fileName = md5(uniqid()) . '.' . $image->guessExtension();
        	$image->move($this->getParameter('brochures_directory'), $fileName);
        	// This way to remove file work
        	$fs = new Filesystem();
        	$fs->remove("uploads/brochures/".$ancienneImage);
        	$recette->setImage($fileName);
        	$em->persist($recette);
        	$em->flush();
        	return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
        			"id" => $id
        	));
        }
        /**
         *
         * @var Ingredient
         */
        $ingredient = new Ingredient();
        $createIngredientForm = $this->createForm('App\Form\IngredientType', $ingredient,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $createIngredientForm->handleRequest($request);
        if ($createIngredientForm->isSubmitted() && $createIngredientForm->isValid()) {
            $ingredient->setRecette($recette);
            $em->persist($ingredient);
            $recette->getIngredients()->add($ingredient);
            $em->persist($recette);
            $em->flush();
            return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
                "id" => $id
            ));
        }
        /**
         *
         * @var \App\Entity\Step $step
         */
        $step = new Step();
        $decrireLeStepForm = $this->createForm('App\Form\StepType', $step,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
        $decrireLeStepForm->handleRequest($request);
        if ($decrireLeStepForm->isSubmitted() && $decrireLeStepForm->isValid()) {
            $step->setRecette($recette);
            $recettes = $em->getRepository(Recette::class)->findAll();
            $position = count($recettes);
            $step->setPosition($position);
            $em->persist($step);
            $recette->getSteps()->add($step);
            $em->persist($recette);
            $em->flush();
            return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
                "id" => $id
            ));
        }
        return $this->render('configuration/recettes/configure-recette.html.twig', array(
            'page' => 'confg-recette',
            'recette' => $recette,
        	'createIngredientForm' => $createIngredientForm->createView(),
        	'editerRecetteForm' => $editerRecetteForm->createView(),
            'decrireLeStepForm' => $decrireLeStepForm->createView()
        ));
    }

    
    /**
     * @Route("{_locale}/editer-step/{stepId}/{recetteId}", name="configure_recette_controller_editer_step")
     */
    public function editerStepAction($stepId,$recetteId,Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$step = $em->getRepository(Step::class)->find($stepId);
    	$decrireLeStepForm = $this->createForm('App\Form\StepType', $step,array('translator'=>new Translator($_locale.'_'.strtoupper($_locale))));
    	$decrireLeStepForm->handleRequest($request);
    	if ($decrireLeStepForm->isSubmitted() && $decrireLeStepForm->isValid()) {
    		$recette = $em->getRepository(Recette::class)->find($recetteId);
    		$step->setRecette($recette);
    		$em->persist($step);
    		$recette->getSteps()->add($step);
    		$em->persist($recette);
    		$em->flush();
    		return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
    				"id" => $recetteId
    		));
    	}
    	return $this->render('configuration/recettes/editer-step.html.twig', array(
    			'page' => 'editer-step',
    			'decrireLeStepForm' => $decrireLeStepForm->createView()
    	));
    }
    
    /**
     * @Route("{_locale}/change-la-position/{stepId}/{recetteId}/{direction}", name="configure_recette_controller_changer-position")
     */
    public function changerPositionActionAction($stepId,$recetteId,Request $request){
    	
    	//find step by id
    	//get step postion
    	// if direction == top
    	// find step2 by postion -1 
    	//step.setPosition(position)
    	//save
    	//step.setPosition(position-1)
    	//save
    	// if direction == bottom
    	// do the same but with position + 1
    }
    
    /**
     * @Route("{_locale}/configurer-la-recette/{recetteId}/{ingredientId}", name="mas_remove_ingredient")
     */
    public function removeIngredientAction(Request $request, $recetteId, $ingredientId)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         *
         * @var Ingredient
         */
        $ingredient = $em->getRepository(Ingredient::class)->find($ingredientId);
        if ($ingredient != null) {
            $em->remove($ingredient);
            $em->flush();
            return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
                "id" => $recetteId
            ));
        }
        return null;
    }
    
    /**
     * @Route("{_locale}/remove-recette/{recetteId}", name="configuration_recette_controller_remove")
     */
    public function removeRecetteAction(Request $request, $recetteId)
    {
    	$em = $this->getDoctrine()->getManager();
    	/**
    	 *
    	 * @var Recette $recette
    	 */
    	$recette = $em->getRepository(Recette::class)->find($recetteId);
    	if ($recette != null) {
    		$fs = new Filesystem();
    		$fs->remove("uploads/brochures/".$recette->getImage());
    		$em->remove($recette);
    		$em->flush();
    	}
    	
    	return $this->redirectToRoute('configuration_controller_init', array(
    			"cfg" => 'rct',
    			'_locale'=>$request->getLocale(),
    			'alertType' => 'succes',
    			'message' => 'recette enlevée avec succes'
    			
    	));
    }

}
