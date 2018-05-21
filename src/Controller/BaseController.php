<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BaseController extends Controller
{
    /**
     * @Route("/recherche/ajax", name="base_controller_recherche")
     */
    public function rechercheAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $search = $request->get('search');
            $jsonData = array();
            if(empty(trim($search))){
                return new JsonResponse($jsonData);
            }
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery('SELECT p
                              FROM App:Produit p
                              WHERE p.nom like :search')->setParameter('search', $search.'%');
            $produits = $query->getResult();
            $idx = 0;
            foreach ($produits as $produit) {
                $temp = array(
                    'nom' => $produit->getNom(),
                    'prix' => $produit->getPrix(),
                    'image' => $produit->getImage(),
                    'reference' => $produit->getReference(),
                    'categorie' => $produit->getCategorie(),
                    'etat' => $produit->getEtat(),
                    'quantite' => $produit->getQuantite(),
                );
                $jsonData[$idx++] = $temp;
            }
            $response = new JsonResponse($jsonData);
            return $response;
        } else {
            return $this->redirectToRoute('start_controller_start', array(
                'page' => 'start'
            ));
        }
    }
    
}
