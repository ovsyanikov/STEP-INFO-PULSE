<?php

namespace model\entity;

class global_news{
       
    public $id;
    public $title;
    public $description;
    public $public_date;    
    public $district;
    public $Source;
    public $Images;
    public $Date;    
    public $Stop_words;
    public $District_str;
    public $SearchType;
    public $IsHide;
    public $Tag;
    public $AllCollors = array('grey','red','blue');
    
    public function getColors(){return array('grey','red','blue');}
    
    function setImages($Images) {
        $this->Images = $Images;
    }

    function setIsHide($IsHide) {
        $this->IsHide = $IsHide;
    }

        function getImages() {
        return $this->Images;
    }

    function getIsHide() {
        return $this->IsHide;
    }

        
    function getImage() {
        return $this->Images;
    }
    
    function getSearchType() {
        return $this->SearchType;
    }
    
    function setSearchType($SearchType) {
        $this->SearchType = $SearchType;
    }

            function setImage($Images) {
        $this->Images = $Images;
    }

    
    function __construct() {
        
    }
    
    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getDate() {
        return $this->Date;
    }

    function getPublic_date() {
        return $this->public_date;
    }

    function getDistrict() {
        return $this->district;
    }

    function getStop_words() {
        return $this->Stop_words;
    }

    function getDistrict_str() {
        return $this->District_str;
    }
    function getSource() {
        return $this->Source;
    }
    
    function setId($id) {
        $this->id = $id;
    }

    function setDate($date) {
        $this->Date = $date;
    }
    
    
    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setPublic_date($public_date) {
        $this->public_date = $public_date;
    }

    function setDistrict($district) {
        $this->district = $district;
    }

    function setSource($Source) {
        $this->Source = $Source;
    }
    
    function setStop_words($Stop_words) {
        return $this->Stop_words = $Stop_words;
    }

    function setDistrict_str($District_str) {
        return $this->District_str = $District_str;
    }
}