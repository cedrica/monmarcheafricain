<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Produit;
use Symfony\Component\Translation\Translator;
use App\Service\ControllerHelper;

class CategoriesController extends Controller
{

    /**
     * @Route("/{_locale}/categories/{catId}", name="categories_controller_categories")
     */
	public function categoriesAction(Request $request, ControllerHelper $helper, $catId,$_locale)
    {
    	$request->setLocale($_locale);
        $affichage = $request->query->get('affichage');
        $produitRepositorty = $this->getDoctrine()->getRepository(Produit::class);
        $produits =  $produitRepositorty->findByCategoryId($catId);
        $productCount = array('%productCount%' => sizeof($produits));
        return $this->render('categories/categories.html.twig', array(
            'page' => 'categories',
            'cat' => $catId,
            'affichage' => $affichage,
        	'produits' => $produits,
            'productCount' => $productCount,
            'activehome' => '',
            'activecategorie' => 'active',
            'activerecettes' => '',
            'activelivraison' => '',
            'activecontact' => ''
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
	    			'productCount' => $productCount,
	    			'activehome' => '',
	    			'activecategorie' => 'active',
	    			'activerecettes' => '',
	    			'activelivraison' => '',
	    			'activecontact' => ''
	    	));
    	}
    	return $this->redirectToRoute('categories_controller_categories', array(
    			'page' => 'categories',
    			'cat' => null,
    			'affichage' => null,
    			'activehome' => '',
    			'activecategorie' => 'active',
    			'activerecettes' => '',
    			'activelivraison' => '',
    			'activecontact' => ''
    	));
    }
}
