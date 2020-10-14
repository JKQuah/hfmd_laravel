<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
        $user = User::get();
        $admin_count = $user->where('role', 'admin')->count();
        $staff_count = $user->where('role', 'staff')->count();
        $active_user_count = $user->where('role', 'public')->where('status', 'active')->count();
        $new_user_count = $user->where('role', 'public')->where('status', 'pending')->count();
        
        return view('admin.home', compact([
            'admin_count',
            'staff_count',
            'active_user_count',
            'new_user_count'
        ]));
    }
}
