<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $datas = Data::get();
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();

        /** get total cases from available years */
        $total_infected = count($datas->where('status', 'infected'));
        $total_deaths = count($datas->where('status', 'death'));
        $total_cases = $total_infected + $total_deaths;
        $overall = [
            'min_year' => $years->first()->year,
            'max_year' => $years->last()->year,
            'total' => $total_cases,
            'infected' => $total_infected,
            'death' => $total_deaths,
        ];

        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year', 'DESC')->get();
        /** get total cases for every years */
        $summary_by_year = [];
        foreach ($years as $year) {
            $total_infected_per_year = Data::select('notificationDate', 'status')
                                            ->where('status', 'infected')
                                            ->whereYear('notificationDate', $year->year)
                                            ->get()->count();
            $total_deaths_per_year = Data::select('notificationDate', 'status')
                                            ->where('status', 'death')
                                            ->whereYear('notificationDate', $year->year)
                                            ->get()->count();
            $summary_by_year[] = [
                'year' => $year->year,
                'total' => $total_infected_per_year + $total_deaths_per_year,
                'infected' => $total_infected_per_year,
                'death' => $total_deaths_per_year
            ];
        }
        
        /** */

        return view('dashboard.index', [
            'overall' => $overall,
            'summary' => $summary_by_year,
        ]);
    }

    public function getLineChart(){
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $total = [];
        $infected = [];
        $death = [];
        $years_exist = [];
        
        foreach ($years as $year) {
            $total_infected_per_year = Data::select('notificationDate', 'status')
                                            ->where('status', 'infected')
                                            ->whereYear('notificationDate', $year->year)
                                            ->get()->count();
            $total_deaths_per_year = Data::select('notificationDate', 'status')
                                            ->where('status', 'death')
                                            ->whereYear('notificationDate', $year->year)
                                            ->get()->count();
            $infected[] += $total_infected_per_year;
            $death[] += $total_deaths_per_year;
            $total[] += $total_infected_per_year + $total_deaths_per_year;
            $years_exist[] += $year->year;
        }
        $role = Auth::user()->role;
        if ($role == 'public') {
            $download = false;
        } else {
            $download = true;
        }
        return response()->json([
            'years'=> $years_exist,
            'total' => $total,
            'infected' => $infected,
            'death' => $death,
            'download' => $download
        ]);
    }

    public function getAgeChart(){
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $years_exist = [];
        $children_below_1 = [];
        $children_below_2 = [];
        $children_below_3 = [];
        $children_below_4 = [];

        $data = Data::get();
        foreach ($years as $year) {
            $children_age_1 = $data->filter(function($item) use ($year) {
                                    return intval(date('Y', strtotime($item['notificationDate']))) == $year->year;
                                })->filter(function($item) {
                                    $diff = abs(strtotime($item['notificationDate']) - strtotime($item['birthday']));
                                    $age = floor($diff / (365*60*60*24));
                                    return  $age <= 0;
                                })->count();
            $children_age_2 = $data->filter(function($item) use ($year) {
                                    return intval(date('Y', strtotime($item['notificationDate']))) == $year->year;
                                })->filter(function($item) {
                                    $diff = abs(strtotime($item['notificationDate']) - strtotime($item['birthday']));
                                    $age = floor($diff / (365*60*60*24));
                                    return  $age > 0 && $age <= 1;
                                })->count();
            $children_age_3 = $data->filter(function($item) use ($year) {
                                    return intval(date('Y', strtotime($item['notificationDate']))) == $year->year;
                                })->filter(function($item) {
                                    $diff = abs(strtotime($item['notificationDate']) - strtotime($item['birthday']));
                                    $age = floor($diff / (365*60*60*24));
                                    return  $age > 1 && $age <= 2;
                                })->count();
            $children_age_4 = $data->filter(function($item) use ($year) {
                                    return intval(date('Y', strtotime($item['notificationDate']))) == $year->year;
                                })->filter(function($item) {
                                    $diff = abs(strtotime($item['notificationDate']) - strtotime($item['birthday']));
                                    $age = floor($diff / (365*60*60*24));
                                    return  $age > 2;
                                })->count();
            $children_below_1[] = $children_age_1;
            $children_below_2[] = $children_age_2;
            $children_below_3[] = $children_age_3;
            $children_below_4[] = $children_age_4;

            $years_exist[] += $year->year;
        }
        return response()->json([
            'years'=> $years_exist,
            'children_below_1' => $children_below_1,
            'children_below_2' => $children_below_2,
            'children_below_3' => $children_below_3,
            'children_above_3' => $children_below_4,
        ]);
    }
}
