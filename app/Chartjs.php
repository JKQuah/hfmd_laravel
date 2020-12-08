<?php

namespace App;

class Chartjs
{
    // Properties
    public $label;
    public $backgroundColor;
    public $borderColor;
    public $data;
    public $fill;

    // Methods
    function set_label($label)
    {
        $this->label = $label;
    }

    function set_backgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    function set_borderColor($borderColor)
    {
        $this->borderColor = $borderColor;
    }

    function set_data($data)
    {
        $this->data = $data;
    }

    function set_fill($fill)
    {
        $this->fill = $fill;
    }

}
