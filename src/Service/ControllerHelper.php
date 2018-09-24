<?php
namespace App\Service;

use App\Entity\Adresse;
use App\Entity\Compte;
use App\Entity\Login;
use App\Entity\Produit;
use App\Repository\LoginRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\Common\Collections\ArrayCollection;

class ControllerHelper
{

    /**
     *
     * @param
     *            $nom
     * @return bool
     */
    public function caculeLaQuantite($panier)
    {
        $quantite = 0;
        if ($panier == null)
            return $quantite;
        foreach ($panier->getPanierItems() as $panierItem) {
            $quantite += $panierItem->getQuantite();
        }
        return $quantite;
    }

    /**
     *
     * @param
     *            $nom
     * @return bool
     */
    public function emailExisteDeja($email, $em)
    {
        if ($email == null || ! self::isNotEmpty($email))
            return false;
        return $em->getRepository(Login::class)->findByEmail($email);
    }

    /**
     *
     * @param string $email
     * @param
     *            $em
     * @return Login
     */
    public function trouveLeLoginByEmail($email, $em)
    {
        if ($email == null || ! self::isNotEmpty($email))
            return false;
        return $em->getRepository(Login::class)->findOneByEmail($email);
    }

    
    /**
     *
     * @param
     *            $email
     * @param
     *            $em
     * @return Compte
     */
    public function trouveLeCompteByEmail($email, $em)
    {
        if ($email == null || ! self::isNotEmpty($email))
            return null;
        $repository = $em->getRepository(Compte::class);
        return $repository->findByEmail($email);
    }

    /**
     *
     * @param
     *            $nom
     * @return bool
     */
    public function motDepassValid($motDePass, $email, $em)
    {
        if ($email == null || ! self::isNotEmpty($email))
            return false;
        if ($motDePass == null || ! self::isNotEmpty($motDePass))
            return false;
        $logins = $em->getRepository(Login::class)->findByEmailEtMotDePass($email,$motDePass);
        return $logins != null && $logins->length() > 0;
    }

    function isNotEmpty($input)
    {
        $strTemp = $input;
        $strTemp = trim($strTemp);
        return $strTemp !== '';
    }
    
    /**
     *
     * @param
     *            $nom
     * @return bool
     */
    public function existeDeja($nom, $em)
    {
    	$repository = $em->getRepository(Produit::class);
    	$query = $repository->createQueryBuilder('p')
    	->where('p.nom = :nom')
    	->setParameter('nom', $nom)
    	->getQuery();
    	$product = $query->setMaxResults(1)->getOneOrNullResult();
    	return $product != null;
    }
    
    public function convertXmlToObject($xmlFile)
    {
    	$xml=simplexml_load_file($xmlFile) or die("Error: Cannot create object");
    	$categoryNodeList = new ArrayCollection();
    	foreach($xml->children() as $cn) {
    		$categoryNode = new CategoryNode();
    		$categoryNode->setId($cn->id);
    		$categoryNode->setParentId($cn->parentId);
    		$categoryNode->setEn($cn->en);
    		$categoryNode->setFr($cn->fr);
    		$categoryNode->setDe($cn->de);
    		$categoryNodeList->add($categoryNode);
    	} 
    	return $categoryNodeList;
    }
    
    public function addNewObjectToXml($xmlFile, $obj)
    {
    	$xml=simplexml_load_file($xmlFile) or die("Error: Cannot create object");
    	$category = $xml->addChild('category');
    	$category->addChild('id',$obj->getId());
    	$category->addChild('parentId',$obj->getParentId());
    	$category->addChild('en',$obj->getEn());
    	$category->addChild('de',$obj->getDe());
    	$category->addChild('fr',$obj->getFr());
    }
    
    public function findLastId($xml){
    	$dom = new DOMDocument;
    	$dom->loadXML($xml);
    	$ids = $dom->getElementsByTagName('id');
    	$idList = new ArrayCollection();
    	foreach ($ids as $id) {
    		$idList->add(bindec($id->nodeValue));
    	}
    	return max($idList);
    }
    public function findCatById($catId,$xmlFile,$lang)
    {
    	$xml=simplexml_load_file($xmlFile) or die("Error: Cannot create object");
    	foreach($xml->children() as $cn) {
    		if(strcmp($cn->id , $catId)){
    			if($lang == 'en'){
    				return $cn->en;
    			}else if($lang == 'de'){
    				return $cn->de;
    			}else if($lang == 'fr'){
    				return $cn->fr;
    			}
    		}
    	}
    }
    
    
}

?>