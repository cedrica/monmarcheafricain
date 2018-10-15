<?php

namespace App\Controller;

use App\Service\ControllerHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Translation\Translator;

class ConfigurationCategoriesController extends Controller
{

    /**
     * @Route("/{_locale}/edit-category/{id}", name="configuration_categories_controller_edit")
     */
	public function editCategoryAction(Request $request, ControllerHelper $helper,$id, $_locale)
    {
    	if($request->isMethod('POST')){
    		$en = $request->request->get('en');
    		$de = $request->request->get('de');
    		$fr = $request->request->get('fr');
    		$parentId = $request->request->get('parentId');
    		
    		$catalogueCategories = $helper->convertXmlToObjectList('catalogs/categories.xml');
    		foreach ($catalogueCategories as $cCat) {
    			if($cCat->getId() == $id){
    				$cCat->setEn($en);
    				$cCat->setDe($de);
    				$cCat->setFr($fr);
    				$cCat->setParentId($parentId);
    			}
    		}
    		$helper->addNewObjectToXml('catalogs/categories.xml',$catalogueCategories);
    		return $this->redirectToRoute('configuration_controller_init');
    	}
    	
    }


   

}
