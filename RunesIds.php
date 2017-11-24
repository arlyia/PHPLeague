<?php

    class Runes
    {
        public $Precision = array();
        public $Domination = array();
        public $Sorcery = array();
        public $Resolve = array();
        public $Inspiration = array();
        
        public function FillRuneArrays()
        {
            $Precision["Keystones"] = array(8005 => "Press the Attack", 8008 => "Lethal Tempo", 8021 => "Fleet Footwork");
            $Precision["Heroism"] = array();
            $Precision["Legend"] = array();
            $Precision["Combat"] = array();
        }
    }
?>