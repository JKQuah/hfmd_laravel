<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FAQ;

class FaqController extends Controller
{
    // 
    public function index(){
        $faqs = FAQ::get();
        $trashed_faqs = FAQ::onlyTrashed()->get();
        return view('admin.faq', compact('faqs', 'trashed_faqs'));
    }

    public function store(Request $request){
        if(!isset($request->answer)){
            return redirect()->back()->with('error', 'Answer must be provided.')->withInput();
        }
        $faq = new FAQ();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->useful = 0;
        $faq->unuseful = 0;
        $faq->save();
        return redirect()->back()->with('success', 'Stored successfully');
    }

    public function update(Request $request, $id){
        FAQ::findOrFail($id)
            ->update([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function destroy($id){
        $faq = FAQ::findOrFail($id);
        $faq->delete();
        return redirect()->back()->with('success', 'Deleted successfully');
    }

    public function restore($id){
        FAQ::onlyTrashed()->findOrFail($id)->restore();
    }
}
