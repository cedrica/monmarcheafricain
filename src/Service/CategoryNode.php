<?php

namespace App\Service;

class CategoryNode
{
    private $id;
    private $parentId;
    private $en;
    private $de;
    private $fr;
    
    public function getId() {
    	return $this->id;
    }
    public function setId($id) {
    	$this->id = $id;
    }

    public function getParentId() {
    	return $this->parentId;
    }
    public function setParentId($parentId) {
    	$this->parentId = $parentId;
    }
    
    public function getEn() {
    	return $this->en;
    }
    public function setEn($en) {
    	$this->en = $en;
    }
    
    public function getDe() {
    	return $this->de;
    }
    public function setDe($de) {
    	$this->de = $de;
    }
    
    public function getFr() {
    	return $this->fr;
    }
    public function setFr($fr) {
    	$this->fr = $fr;
    }
    
    
}

