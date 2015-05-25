<?php

namespace model\entity;

class statistic_stop_word{
    
    private $user_id;
    private $word_id;
    
    function __construct() {
        
    }
    
    function getUser_id() {
        return $this->user_id;
    }

    function getWord_id() {
        return $this->word_id;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }

    function setWord_id($word_id) {
        $this->word_id = $word_id;
    }



    
}