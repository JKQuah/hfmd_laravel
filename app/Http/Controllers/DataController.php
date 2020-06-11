<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;
use Carbon\Carbon;
use DB;

class DataController extends Controller
{
    
    public function index(){
        set_time_limit(300);

        /** get all notification date based on years*/
        // $yearData = Data::select('notificationDate')
        //                 ->orderBy('notificationDate')->get()
        //                 ->groupBy(function ($val) {
        //                     return Carbon::parse($val->notificationDate)->format('y');
        //                 });

        /** retrieve all available states, month & year */
        $states = Data::select('state')->orderBy('state')->groupBy('state')->get();
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $months = Data::select(DB::raw('MONTH(notificationDate) as month'))->distinct()->orderBy('month')->get();

        /** retrieve all data by existing years, states, months */
        /** push key & value pair (states, data respective) */
        $datalist = array();
        foreach($years as $year){
            $data_state_months = array();
            $sum_data_per_state = [];
            foreach($states as $state){
                $data_months = array();
                $sum = 0;
                foreach($months as $month){
                    $data = Data::select('state', 'notificationDate')
                                    ->where('state', $state->state)
                                    ->whereYear('notificationDate', '=', $year->year)
                                    ->whereMonth('notificationDate', '=', $month->month)
                                    ->get()->count();
                    array_push($data_months, $data);
                    $sum += $data;
                }
                $sum_data_per_state += array($state->state => $sum);
                $data_state_months[] = array($state->state => $data_months);
            }
            $total_infected = Data::select('notificationDate', 'status')
                            ->where('status', 'infected')
                            ->whereYear('notificationDate', '=', $year->year)
                            ->get()->count();
            $total_deaths = Data::select('notificationDate', 'status')
                            ->where('status', 'death')
                            ->whereYear('notificationDate', '=', $year->year)
                            ->get()->count();
            $male = Data::select('notificationDate', 'gender')
                            ->where('gender', '=', 'male')
                            ->whereYear('notificationDate', '=', $year->year)
                            ->get()->count();
            $female = Data::select('notificationDate', 'gender')
                            ->where('gender', '=', 'female')
                            ->whereYear('notificationDate', '=', $year->year)
                            ->get()->count();
            
            $datalist[] = array(
                'year' => $year->year,
                'total_infected' => $total_infected,
                'total_deaths' => $total_deaths,
                'total_male' => $male,
                'total_female' => $female,
                'data' => $data_state_months,
                'maxkey' => array_keys($sum_data_per_state, max($sum_data_per_state))[0],
                'maxvalue' => max($sum_data_per_state),
                'minkey' => array_keys($sum_data_per_state, min($sum_data_per_state))[0],
                'minvalue' => min($sum_data_per_state),
            );
        }

        // return $states;
        
        return view('data.index', [
            'years'  => $years,
            'months' => $months,
            'datalist' => $datalist
        ]);
    }

    public function show($year, $state){

        $states = Data::select('state')->orderBy('state')->groupBy('state')->get();
        $months = Data::select(DB::raw('MONTH(notificationDate) as month'))->distinct()->orderBy('month')->get();
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $districts = Data::select('district')->where('state', $state)->orderBy('district')->groupBy('district')->get();

        $total_infected = Data::select('state','notificationDate')
                                ->where('state', $state)
                                ->where('status', 'infected')
                                ->whereYear('notificationDate', '=', $year)
                                ->get()->count();

        $total_deaths = Data::select('status', 'notificationDate')
                                ->where('state', $state)
                                ->where('status', 'death')
                                ->whereYear('notificationDate', '=', $year)
                                ->get()->count();

        $data_district_months = array();
        $sum_data_per_district = [];
            foreach($districts as $district){
                $data_months = array();
                $sum = 0;
                foreach($months as $month){
                    $data = Data::select('district', 'notificationDate' )
                                    ->where('district', $district->district)
                                    ->whereYear('notificationDate', '=', $year)
                                    ->whereMonth('notificationDate', '=', $month->month)
                                    ->get()->count();
                    array_push($data_months, $data);
                    $sum += $data;
                }
                $sum_data_per_district += array($district->district => $sum);
                array_push($data_district_months, array($district->district => $data_months));
        }

        $category_A = []; $range_A = 100;
        $category_B = []; $range_B = 200;
        $category_C = []; $range_C = 300;
        $category_D = []; 
        foreach ($sum_data_per_district as $key => $value) {
            if($value < $range_A){
                $category_A += [$key => $value];
            }else if($value < $range_B){
                $category_B += [$key => $value];
            }else if($value < $range_C){
                $category_C += [$key => $value];
            }else {
                $category_D += [$key => $value];
            }
        }
        $category_data = [];
        $category_data[] = array(
            'type' => 'A',
            'range' => '<'.$range_A,
            'count' => count($category_A),
            'data' => $category_A,
        );
        $category_data[] = array(
            'type' => 'B',
            'range' => $range_A.'-'.$range_B,
            'count' => count($category_B),
            'data' => $category_B,
        );
        $category_data[] = array(
            'type' => 'C',
            'range' => ($range_B + 1).'-'.$range_C,
            'count' => count($category_C),
            'data' => $category_C,
        );
        $category_data[] = array(
            'type' => 'D',
            'range' => '>'.$range_C,
            'count' => count($category_D),
            'data' => $category_D,
        );
        // return $category_data;
        // return $sum_data_per_district;

        return view('data.show',[
            'this_state' => $state,
            'states' => $states,
            'this_year' => $year,
            'years' =>$years,
            'months' => $months,
            'data_district_months' => $data_district_months,
            'total_infected' => $total_infected,
            'total_deaths' => $total_deaths,
            'category_data' => $category_data,
        ]);
    }
}
