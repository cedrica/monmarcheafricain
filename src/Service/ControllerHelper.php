<?php
namespace App\Service;

use App\Entity\Adresse;
use App\Entity\Compte;
use App\Entity\Login;
use App\Entity\Produit;
use App\Repository\LoginRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
    
}

?>