<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoriesController extends Controller
{

    /**
     * @Route("/{_locale}/categories/{cat}", name="mas_categories")
     */
    public function categoriesAction(Request $request, $cat,$_locale)
    {
    	$request->setLocale($_locale);
        $affichage = $request->query->get('affichage');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT p
                                FROM App:Produit p
                                WHERE p.categorie = :categorie')->setParameter('categorie', $cat);
        $produits = $query->getResult();
        return $this->render('categories/categories.html.twig', array(
            'page' => 'categories',
            'cat' => $cat,
            'affichage' => $affichage,
            'produits' => $produits
        ));
    }
    
}
