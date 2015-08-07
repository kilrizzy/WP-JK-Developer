<?php

class Template
{

    public $url;

    function get(){
        ob_start();
        include($this->url);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}