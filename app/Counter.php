<?php

namespace App;

class Counter
{
    // Properties
    public $id;
    public $name;
    public $date;
    public $description;
    public $type;

    // Methods
    function set_id($id)
    {
        $this->id = $id;
    }

    function set_name($name)
    {
        $this->name = $name;
    }

    function set_date($date)
    {
        $this->date = $date;
    }

    function set_description($description)
    {
        $this->description = $description;
    }

    function set_type($type)
    {
        $this->type = $type;
    }
}
