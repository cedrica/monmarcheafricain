<?php
namespace App\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('findvalue', array($this, 'findvalueFilter')),
        );
    }

    public function findvalueFilter($toBeTransform,$lang)
    {
    	$xml=simplexml_load_file('catalogs/categories.xml') or die("Error: Cannot create object");
    	foreach($xml->children() as $cn) {
    		if(strcmp($cn->id , $toBeTransform) == 0){
    			if($lang == 'en'){
    				return $cn->en;
    			}else if($lang == 'de'){
    				return $cn->de;
    			}else if($lang == 'fr'){
    				return $cn->fr;
    			}
    		}
    	}
        return "";
    }
}
?>