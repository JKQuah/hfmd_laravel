<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class DataController extends Controller
{

    public function index()
    {

        /** retrieve all available distinct years, states, months */
        $datas = Data::get();
        $states = Data::select('state')->orderBy('state')->groupBy('state')->get();
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $months = Data::select(DB::raw('MONTH(notificationDate) as month'))->distinct()->orderBy('month')->get();

        /** retrieve all data by existing years, states, months */
        $data_array = [];

        foreach ($datas as $data) {
            $data_array[date('Y', strtotime($data->notificationDate))][$data->state][intval(date('m', strtotime($data->notificationDate)))][date('d', strtotime($data->notificationDate))] = $data;
        }

        /** create an empty 3-multideminsional array to make sure all states and months are included */
        $view_array = [];
        foreach ($years as $year) {
            foreach ($states as $state) {
                foreach ($months as $month) {
                    $view_array[$year->year][$state->state][$month->month] = 0;
                }
            }
        }

        $datalist = array();
        /** insert the case count from data_array into view_array */
        foreach ($data_array as $data_years => $details) {
            foreach ($details as $data_states => $data_months) {
                foreach ($data_months as $month => $day) {
                    foreach (array_keys($view_array[$data_years][$data_states]) as $key => $value) {
                        if ($value == $month) {
                            $view_array[$data_years][$data_states][$month] = count($day);
                        }
                    }
                }
            }
        }

        /** insert all required data to display in data.index */
        foreach ($years as $year) {
            $state_per_year = [];
            foreach ($states as $state) {
                $total_case_per_state = 0;
                foreach ($months as $month) {
                    $total_case_per_state += $view_array[$year->year][$state->state][$month->month];
                }
                $state_per_year += [
                    $state->state => $total_case_per_state,
                ];
            }
            $total_infected = Data::select('notificationDate', 'status')
                ->where('status', 'infected')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $total_deaths = Data::select('notificationDate', 'status')
                ->where('status', 'death')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $male = Data::select('notificationDate', 'gender')
                ->where('gender', '=', 'male')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();
            $female = Data::select('notificationDate', 'gender')
                ->where('gender', '=', 'female')
                ->whereYear('notificationDate', $year->year)
                ->get()->count();

            $datalist[] = array(
                'year' =>  $year->year,
                'total_infected' => $total_infected,
                'total_deaths' => $total_deaths,
                'total_male' => $male,
                'total_female' => $female,
                'data' => $view_array[$year->year],
                'maxkey' => array_keys($state_per_year,  max($state_per_year))[0],
                'maxvalue' => max($state_per_year),
                'minkey' => array_keys($state_per_year, min($state_per_year))[0],
                'minvalue' => min($state_per_year),

            );
        }
        // dd($datalist);
        /** return data back into index */
        return view('data.index', [
            'years'  => $years,
            'months' => $months,
            'datalist' => $datalist,
        ]);
    }

    public function show($year, $state)
    {

        // $data = Data::get();
        // $states = Data::select('state')->orderBy('state')->groupBy('state')->get();
        // $months = Data::select(DB::raw('MONTH(notificationDate) as month'))->distinct()->orderBy('month')->get();
        // $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        // $districts = $data->where('state', $state)->sortBy('district')->groupBy('district')->keys();

        // $total_infected = $data
        //     ->where('state', $state)
        //     ->where('status', 'infected')
        //     ->filter(function ($item) use ($year) {
        //         return date('Y', strtotime($item->notificationDate)) == $year;
        //     })
        //     ->count();
        // $total_deaths = $data
        //     ->where('state', $state)
        //     ->where('status', 'death')
        //     ->filter(function ($item) use ($year) {
        //         return date('Y', strtotime($item->notificationDate)) == $year;
        //     })
        //     ->count();

        // $data_district_months = array(); // for display months' data
        // $sum_data_per_district = array(); // to category data

        // foreach ($districts as $district) {
        //     $data_months = array();
        //     $sum = 0;
        //     foreach ($months as $month) {
        //         $this_data = $data
        //                     ->where('district', $district)
        //                     ->filter(function ($item) use ($year, $month) {
        //                         return date('Y', strtotime($item->notificationDate)) == $year
        //                             && intval(date('m', strtotime($item->notificationDate))) == $month->month;
        //                     })
        //                     ->count();
        //         array_push($data_months, $this_data);
        //         $sum += $this_data;
        //     }

        //     array_push($data_district_months, array($district => $data_months));
        //     array_push($sum_data_per_district, array($district => $sum));
        // }

        $states = Data::select('state')->orderBy('state')->groupBy('state')->get();
        $months = Data::select(DB::raw('MONTH(notificationDate) as month'))->distinct()->orderBy('month')->get();
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $districts = Data::select('district')->where('state', $state)->orderBy('district')->groupBy('district')->get();
        $data = Data::get();

        // $total_infected = Data::select('state', 'notificationDate')
        //     ->where('state', $state)
        //     ->where('status', 'infected')
        //     ->whereYear('notificationDate', '=', $year)
        //     ->get()->count();
        $total_infected = $data ->where('state', $state)
                                ->where('status', 'infected')
                                ->filter(function($element) use ($year){
                                    return date('Y', strtotime($element['notificationDate'])) == $year;
                                })->count();
        $total_deaths = $data   ->where('state', $state)
                                ->where('status', 'death')
                                ->filter(function($element) use ($year){
                                    return date('Y', strtotime($element['notificationDate'])) == $year;
                                })->count();
        // $total_deaths = Data::select('status', 'notificationDate')
        //     ->where('state', $state)
        //     ->where('status', 'death')
        //     ->whereYear('notificationDate', '=', $year)
        //     ->get()->count();
        
        $data_district_months = array();
        $sum_data_per_district = [];

        foreach ($districts as $district) {
            $data_months = array();
            $sum = 0;
            foreach ($months as $month) {
                $data_count = Data::select('district', 'notificationDate')
                    ->where('district', $district->district)
                    ->whereYear('notificationDate', '=', $year)
                    ->whereMonth('notificationDate', '=', $month->month)
                    ->get()->count();
                // $data_count = $data ->where('district', $district->district)
                //                     ->filter(function($element) use ($year){
                //                         return date('Y', strtotime($element['notificationDate'])) == $year;
                //                     })->filter(function($element) use ($month){
                //                         return intval(date('m', strtotime($element['notificationDate']))) == $month->month;
                //                     })->count();
                
                array_push($data_months, $data_count);
                $sum += $data_count;
            }
            
            $sum_data_per_district += array($district->district => $sum);
            array_push($data_district_months, array($district->district => $data_months));
            
        }
        
        $category_A = [];
        $range_A = 100;
        $category_B = [];
        $range_B = 200;
        $category_C = [];
        $range_C = 300;
        $category_D = [];
        foreach ($sum_data_per_district as $key => $value) {
            if ($value < $range_A) {
                $category_A += [$key => $value];
            } else if ($value < $range_B) {
                $category_B += [$key => $value];
            } else if ($value < $range_C) {
                $category_C += [$key => $value];
            } else {
                $category_D += [$key => $value];
            }
        }
        $category_data = [];
        $category_data[] = array(
            'type' => 'A',
            'range' => '<' . $range_A,
            'count' => count($category_A),
            'data' => $category_A,
        );
        $category_data[] = array(
            'type' => 'B',
            'range' => $range_A . '-' . $range_B,
            'count' => count($category_B),
            'data' => $category_B,
        );
        $category_data[] = array(
            'type' => 'C',
            'range' => ($range_B + 1) . '-' . $range_C,
            'count' => count($category_C),
            'data' => $category_C,
        );
        $category_data[] = array(
            'type' => 'D',
            'range' => '>' . $range_C,
            'count' => count($category_D),
            'data' => $category_D,
        );
        // dd($category_data);
        // dd( $sum_data_per_district);

        return view('data.show', [
            'this_state' => $state,
            'states' => $states,
            'this_year' => $year,
            'years' => $years,
            'months' => $months,
            'data_district_months' => $data_district_months,
            'total_infected' => $total_infected,
            'total_deaths' => $total_deaths,
            'category_data' => $category_data,
            // 'data_graph' => $sum_data_per_district,
        ]);
    }

    public function getLocalityChart(Request $request){
        $this_year = $request->year;
        $this_state = $request->state;
        $data = Data::get() ->where('state', $this_state)
                            ->filter(function($element) use ($this_year){
                                return date('Y', strtotime($element['notificationDate'])) == $this_year;
                            });
        $array = [];
        foreach($data as $case){
            
        }
        
    }
}
