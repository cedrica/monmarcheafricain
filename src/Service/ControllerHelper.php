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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
     *            $loginId
     * @param
     *            $em
     * @return Compte
     */
    public function trouveLeCompteByLogin($loginId, $em)
    {
    	if ($loginId == null || ! self::isNotEmpty($loginId))
            return null;
        $repository = $em->getRepository(Compte::class);
        return $repository->findByLoginId($loginId);
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
    
    public function convertXmlToObjectList($xmlFile)
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
    
    public function addNewObjectToXml($xmlFile, $objectList)
    {
    	$xml = "";
    	foreach($objectList as $obj){
    		$xml  .= "\n	<category>\n		<id>".$obj->getId().
    		"</id>\n		<parentId>".$obj->getParentId().
    		"</parentId>\n		<en>".$obj->getEn().
    		"</en>\n		<fr>".$obj->getFr().
    		"</fr>\n		<de>".$obj->getDe().
    		"</de>\n	</category>";
    	}
    	$xml ="<?xml version='1.0' encoding='UTF-8'?>\n
					<categories>\n".$xml."\n</categories>";
    	$fileSystem = new FileSystem();
    	$fileSystem->dumpFile($xmlFile, $xml);
    }
    public function calculTotalPrice($panierItems){
    	$price = 0;
    	
    	foreach ($panierItems as $panierItem) {
    		$price += $panierItem->getProduit()->getPrix();
    	}
    	return $price;
    }
    
    public function findDescriptionByLanguage($produit, $lang){
    	/*if($lang == 'en'){
    		return $produit->getDescriptionEN();
    	}else if($lang == 'de'){
    		return $produit->getDescriptionDE();
    	}else if($lang == 'fr'){
    		return $produit->getDescriptionFR();
    	}*/
    	return "none";
    }
    
    public function transformIntoTransaction($session, $tax, $shipping, $lang){
    	$panierItems = $session->get('panier')->getPanierItems();
    	$totalPrice = self::calculTotalPrice($panierItems);
    	$transactions = 
    	"[{
    			amount:
    			{
    				total: $totalPrice,
    				currency: EUR,
    				details:
	    			{
		    			tax: 9,
		    			shipping: $shipping
	    			}
    			},
    			custom: EBAY_EMS_90048630024435,
    			invoice_number: 48787589673,
    			payment_options:
    			{
    				allowed_payment_method: INSTANT_FUNDING_SOURCE
    			},
    			soft_descriptor: ECHI5786786,
    			item_list:
    			{
    				items: [";
    	$counter = 0;
    	foreach ($panierItems as $panierItem) {
    		$transactions .= "{
		    			name: ".$panierItem->getProduit()->getName().",
		    			description: ".self::findDescriptionByLanguage($panierItem->getProduit(),$lang).",
		    			quantity: ". $panierItem->getQuantite().",
		    			price: ".$panierItem->getProduit()->getPrix().",
		    			currency: EUR
    				}";
    		$counter++;
    		if($counter < count($panierItems)){
    			$transactions .= ",";
    		}
    	}
    			
    	$transactions .= "],
    			shipping_address:
    			{
	    			recipient_name: ".str_replace(" ","_",$session->get('compte')->getNom()." ".$session->get('compte')->getPrenom()).",
	    			city: ".$session->get('deliveryAdress')->getVille().",
					postal_code: ".$session->get('deliveryAdress')->getBoitePostale().",
					street: ".str_replace(" ","_",$session->get('deliveryAdress')->getRueEtNr())."
    			}
    		}
    	}]";
    	return $transactions;
    	
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