<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Produit;
use Symfony\Component\Translation\Translator;

class CategoriesController extends Controller
{

    /**
     * @Route("/{_locale}/categories/{cat}", name="categories_controller_categories")
     */
	public function categoriesAction(Request $request, $cat,$_locale)
    {
    	$translator = new Translator($_locale);
    	$request->setLocale($_locale);
        $affichage = $request->query->get('affichage');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT p
                                FROM App:Produit p
                                WHERE p.categorie = :categorie')->setParameter('categorie', $cat);
        $produits = $query->getResult();
        $productCount = array('%productCount%' => sizeof($produits));
        return $this->render('categories/categories.html.twig', array(
            'page' => 'categories',
            'cat' => $cat,
            'affichage' => $affichage,
        	'produits' => $produits,
        		'productCount' => $productCount
        ));
    }
    /**
     * @Route("/{_locale}/filtre/{cat}", name="categories_controller_filtre")
     */
    public function filtreAction(Request $request, $cat,$_locale)
    {
    	if ($request->getMethod() == 'POST') {
	    	$minPrice = $request->request->get('minprice');
	    	$maxPrice = $request->request->get('maxprice');
	    	$sortBy = $request->request->get('sortby');
	    	$sortBy = ($sortBy == "null")? 'asc':$sortBy;
	    	var_dump($sortBy);
	    	$action = $request->request->get('action');
	    	$request->setLocale($_locale);
	    	$affichage = $request->query->get('affichage');
	    	$produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
	    	$produits =  $produitRepositorty->findByFilter($cat,$minPrice,$maxPrice,$sortBy);
	    	$productCount = array('%productCount%' => sizeof($produits));
	    	return $this->render('categories/categories.html.twig', array(
	    			'page' => 'categories',
	    			'cat' => $cat,
	    			'affichage' => $affichage,
	    			'produits' => $produits,
	    			'productCount' => $productCount
	    	));
    	}
    	return $this->redirectToRoute('categories_controller_categories', array(
    			'page' => 'categories',
    			'cat' => null,
    			'affichage' => null
    	));
    }
}
