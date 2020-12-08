<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClimaticController extends Controller
{
    //
    public function index(){
        return view('climatic.index');
    }
}
