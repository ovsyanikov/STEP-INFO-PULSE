<?php

namespace model\entity;

class bad_word{
    
    public $id;
    public $Word;
    
    function __construct() {
        
    }

    function getId() {
        return $this->id;
    }

    function getWord() {
        return $this->Word;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setWord($word) {
        $this->Word = $word;
    }


}