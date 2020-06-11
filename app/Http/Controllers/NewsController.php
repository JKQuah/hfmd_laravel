<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;

class NewsController extends Controller
{
    public function index() {

        $states = State::all();

        return view('/news', [
            'states' => $states
        ]);
    }
}
