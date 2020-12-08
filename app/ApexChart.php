<?php

namespace App;

class ApexChart
{
    public $name;
    public $data;
    public $color;
    public $type;

    function __construct($name, $data){
        $this->name = $name;
        $this->data = $data;
    }

    function set_color($color){
        $this->color = $color;
    }

    function set_type($type){
        $this->type = $type;
    }
}