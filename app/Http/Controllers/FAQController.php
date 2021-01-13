<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FAQ;

class FAQController extends Controller
{
    public function index(){
        $faqs = FAQ::paginate(5);
        
        return view('faq.index', compact('faqs'));
    }

    public function toggleLike(Request $request){
        if($request->like == "true"){
            FAQ::find($request->id)->increment('useful');
        } else {
            FAQ::find($request->id)->decrement('useful');
        }
    }

    public function toggleDislike(Request $request){
        if($request->dislike == "true"){
            FAQ::find($request->id)->increment('unuseful');
        } else {
            FAQ::find($request->id)->decrement('unuseful');
        }
    }
}
