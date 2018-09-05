<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoginRepository")
 */
class Login
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     */
    private $email;
    
    /**
     * @var string
     * @ORM\Column(name="motDePass", type="string")
     */
    private $motDePass;
    
    /**
     * @var string
     * @ORM\Column(name="language", type="string", nullable=true)
     */
    private $language;
    
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Set email
     *
     * @param string $email
     *
     * @return Login
     */
    public function setEmail($email)
    {
    	$this->email = $email;
    	
    	return $this;
    }
    
    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
    	return $this->email;
    }
    
    
    /**
     * Set email
     *
     * @param string $email
     *
     * @return Login
     */
    public function setLanguage($language)
    {
    	$this->language = $language;
    }
    
    /**
     * Get email
     *
     * @return string
     */
    public function getLanguage()
    {
    	return $this->language;
    }
    
    /**
     * Set motDePass
     *
     * @param string $motDePass
     *
     * @return Login
     */
    public function setMotDePass($motDePass)
    {
    	$this->motDePass = $motDePass;
    	
    	return $this;
    }
    
    /**
     * Get motDePass
     *
     * @return string
     */
    public function getMotDePass()
    {
    	return $this->motDePass;
    }
    
}

