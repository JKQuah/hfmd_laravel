<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function getAdminPage()
    {
        $admins = User::get()->where('role', 'admin')->sortBy('id');
        return view('admin.admin_list', compact('admins'));
    }

    public function getStaffPage()
    {
        $users = User::get()->where('role', 'staff')->sortBy('id');
        return view('admin.staff', compact('users'));
    }

    public function getUserPage()
    {
        $users = User::get()->where('role', 'public')->sortBy('status');
        $active_users = $users->where('status', 'active')->count();
        $pending_users = $users->where('status', 'pending')->count();

        return view('admin.user', compact('users', 'active_users', 'pending_users'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id)
            ->update([
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'status' => $request->input('status')
            ]);

        return redirect()->back()->with('success', 'Updated Successfully');
    }

    public function store()
    {
        try {
            $user = new User();
            $user->title = request('title');
            $user->fname = request('fname');
            $user->lname = request('lname');
            $user->email = request('email');
            $user->phone = request('phone');
            $user->password = Hash::make('hfmd1234');
            $user->role = request('user_type');
            switch ($user->role) {
                case 'admin':
                case 'staff':
                    $user->status = 'active';
                    break;
                default:
                    $user->status = 'pending';
                    break;
            }
            $user->save();
            return redirect()->back()->with('success', 'Invited Successfully');

        } catch (\Throwable $th) {
            
            $email = request('email');
            switch ($th->errorInfo[0]) {
                case '23000':
                    return redirect()->back()->with('error', 'This email <b>'.$email.'</b> had been registered');
                    break;
                
                default:
                    return redirect()->back()->with('error', 'Unknown Errors had occured.');
                    break;
            }
        }
    }

    public function destroy($id)
    {

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Delete Successfully');
    }

    public function activateSelected(Request $request)
    {
        $user_ids = $request->pendingUser;

        foreach ($user_ids as $user_id) {
            User::find($user_id)->update([
                'status' => 'active'
            ]);
        }
        return redirect()->back()->with('success', 'Activated Successfully');
    }

    public function activateAll() {
        User::where('role', 'public')
            ->where('status', 'pending')
            ->update(['status' => 'active']);
    } 
}
