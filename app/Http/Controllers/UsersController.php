<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersController extends Controller
{
    public function index(){
        $userlist = User::get();
        
        $adminlist = $userlist
                        ->where('role', 'admin')
                        ->where('status', 'active');
                        
        $pending_public_list = $userlist
                        ->where('role', '=', 'public')
                        ->where('status', 'pending');

        $approve_public_list = $userlist
                        ->where('role', '=', 'public')
                        ->where('status', 'approve')
                        ->count();
        
        return view('Users.index', [
            'adminlist' => $adminlist,
            'adminCount' => count($adminlist),
            'approveCount' => $approve_public_list,
            'publiclist' => $pending_public_list,
            'pendingCount' => count($pending_public_list),
        ]);
    }

    public function show($id){

        $admin = User::find($id);

        return view('profiles.show', [
            'admin' => $admin,
            
        ]);
    }

    public function update(Request $request, $id){
        $update = $request->all();
        // return $update;
        $user = User::find($id)
                ->update([
                    'fname' => $request->input('fname'),
                    'lname' => $request->input('lname'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                ]);
                    
        return redirect()->back()->with('mssg', 'Successfully Updated');
    }

    public function store(){
        $admin = new User();

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

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back();
    }
}
