<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\User;
use App\Data;
use App\FAQ;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
        // Users summary
        $user = User::get();
        $admin_count = $user->where('role', 'admin')->count();
        $staff_count = $user->where('role', 'staff')->count();
        $active_user_count = $user->where('role', 'public')->where('status', 'active')->count();
        $new_user_count = $user->where('role', 'public')->where('status', 'pending')->count();

        // Data summary
        $datas = Data::get();
        $total_data = $datas->count();
        $total_infected = $datas->where('status', 'infected')->count();
        $total_death = $datas->where('status', 'death')->count();


        // FAQ summary
        $faqs = FAQ::get();
        $active_faq = $faqs->whereNull('deleted_at')->count();
                
        return view('admin.home', compact([
            'admin_count', 'staff_count', 'active_user_count', 'new_user_count',
            'total_data', 'total_infected', 'total_death',
            'active_faq'
        ]));
    }
}
