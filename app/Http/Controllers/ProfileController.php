<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Profile;

class ProfileController extends Controller
{
    public function index(){
        $adminlist = Profile::select('*')
                        ->where('role', '=', 'admin')
                        ->where('status', '=', 'active')
                        ->get();

        $pending_public_list = Profile::select('id', 'fname', 'lname', 'email', 'phone', 'status')
                        ->where('role', '=', 'public')
                        ->where('status', 'pending')
                        ->get();

        $approve_public_list = Profile::select('id', 'fname', 'lname', 'email', 'phone', 'status')
                        ->where('role', '=', 'public')
                        ->where('status', 'approve')
                        ->get()->count();
        
        return view('profiles.index', [
            'adminlist' => $adminlist,
            'adminCount' => count($adminlist),
            'approveCount' => $approve_public_list,
            'publiclist' => $pending_public_list,
            'pendingCount' => count($pending_public_list),
        ]);
    }

    public function show($id){

        $admin = Profile::find($id);

        return view('profiles.show', [
            'admin' => $admin,
            
        ]);
    }

    public function update(Request $request, $id){
        $update = $request->all();
        // return $update;
        $user = Profile::find($id)
                ->update([
                    'fname' => $request->input('fname'),
                    'lname' => $request->input('lname'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    // 'password' => $request->input('password'),
                    // 'role' => $request->input('role'),
                    // 'status' => $request->input('status'),
                ]);
                    
        return redirect()->back()->with('mssg', 'Successfully Updated');
    }

    public function store(){
        $admin = new Profile();

        $admin->title = request('title');
        $admin->fname = request('fname');
        $admin->lname = request('lname');
        $admin->email = request('email');
        $admin->phone = request('phone');
        $admin->password = Hash::make('hfmd1234');
        $admin->role = 'admin';
        $admin->status = 'active';
        $admin->save();
        
        return redirect()->back();
    }

    public function destroy($id){

        $user = Profile::findOrFail($id);
        $user->delete();

        return redirect()->back();
    }
}
