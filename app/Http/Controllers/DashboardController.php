<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;
use App\Year;

class DashboardController extends Controller
{
    public function index() {
        // $states = State::all();
        // $years = Year::orderBy('years', 'desc')->get();

        // return view( '/dashboard' , [
        //     'states' => $states, 
        //     'years' => $years
        // ]);
    }
}