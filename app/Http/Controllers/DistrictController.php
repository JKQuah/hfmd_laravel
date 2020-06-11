<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;
use DB;

class DistrictController extends Controller
{
    public function show($this_year, $this_state, $district){
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        $district_list = Data::select('district')
                        ->where('state', '=', $this_state)
                        ->groupBy('district')
                        ->get();

        $data_districts = Data::select('district', 'gender','birthday','notificationDate','onsetDate', 'status')
                        ->where('district', '=', $district)
                        ->orderBy('notificationDate')
                        ->get();
        $agelist = array();
        $day_diff = array();
        foreach ($data_districts as $data) {
            $birthday = strtotime($data['birthday']);
            $notificationDate = strtotime($data['notificationDate']);
            $onsetDate = strtotime($data['onsetDate']);
            $day_diff[] = array('day_diff'=>abs($onsetDate-$notificationDate)/60/60/24);
            $diff = abs($notificationDate - $birthday);  
            $year = floor($diff / (365*60*60*24));  
            $month = floor(($diff - $year * 365*60*60*24)/ (30*60*60*24));  
            $day = floor(($diff - $year * 365*60*60*24 - $month*30*60*60*24)/ (60*60*24));
            $agelist[] = array(
                'year'=>$year,
                'month'=>$month,
                'day'=> $day,
            );
        }
        $i=0;
        $datalist = array();
        foreach($data_districts as $data){
            $datalist[] = array(
                'id' => $i+1,
                'gender' => $data['gender'],
                'year' => $agelist[$i]['year'],
                'month' => $agelist[$i]['month'],
                'day' => $agelist[$i]['day'],
                'notificationDate' => $data['notificationDate'],
                'onsetDate' => $data['onsetDate'],
                'day_diff' => $day_diff[$i]['day_diff'], 
                'status' => $data['status']
            );
            $i++;
        }
        $data_district_year = array();
        foreach($years as $year){
            $total_infected_per_year = Data::select('notificationDate')
                                        ->where('district', '=', $district)
                                        ->where('status', '=', 'infected')
                                        ->whereYear('notificationDate', '=', $year->year)
                                        ->get()->count();
            $total_deaths_per_year = Data::select('notificationDate')
                                        ->where('district', '=', $district)
                                        ->where('status', '=', 'death')
                                        ->whereYear('notificationDate', '=', $year->year)
                                        ->get()->count();
            $total_male_per_year = Data::select('gender')
                                        ->where('district', '=', $district)
                                        ->where('gender', '=', 'male')
                                        ->whereYear('notificationDate', '=', $year->year)
                                        ->get()->count();
            $total_female_per_year = Data::select('gender')
                                        ->where('district', '=', $district)
                                        ->where('gender', '=', 'female')
                                        ->whereYear('notificationDate', '=', $year->year)
                                        ->get()->count();

            $data_district_year[] = array(
                'year' => $year->year,
                'infected' => $total_infected_per_year,
                'deaths' => $total_deaths_per_year,
                'male' => $total_male_per_year,
                'female' => $total_female_per_year,

            );
        }
        
        $data_infected = Data::select('notificationDate')
                            ->whereYear('notificationDate','=',$this_year)
                            ->where('state', '=', $this_state)
                            ->where('district', '=', $district)
                            ->where('status', '=', 'infected')
                            ->get()->count();
        $data_deaths = Data::select('notificationDate')
                            ->whereYear('notificationDate','=',$this_year)
                            ->where('state', '=', $this_state)
                            ->where('district', '=', $district)
                            ->where('status', '=', 'death')
                            ->get()->count();
        $data_infected_male = Data::select('notificationDate')
                            ->whereYear('notificationDate','=',$this_year)
                            ->where('state', '=', $this_state)
                            ->where('district', '=', $district)
                            ->where('gender', '=', 'male')
                            ->where('status', '=', 'infected')
                            ->get()->count();
        $data_deaths_male = Data::select('notificationDate')
                            ->whereYear('notificationDate','=',$this_year)
                            ->where('state', '=', $this_state)
                            ->where('district', '=', $district)
                            ->where('gender', '=', 'male')
                            ->where('status', '=', 'death')
                            ->get()->count();
        $data_infected_female = Data::select('notificationDate')
                            ->whereYear('notificationDate','=',$this_year)
                            ->where('state', '=', $this_state)
                            ->where('district', '=', $district)
                            ->where('gender', '=', 'female')
                            ->where('status', '=', 'infected')
                            ->get()->count();
        $data_deaths_female = Data::select('notificationDate')
                            ->whereYear('notificationDate','=',$this_year)
                            ->where('state', '=', $this_state)
                            ->where('district', '=', $district)
                            ->where('gender', '=', 'female')
                            ->where('status', '=', 'death')
                            ->get()->count();
                            
        $this_district = array(
            'infected' => $data_infected,
            'deaths' => $data_deaths,
            'i_male' => $data_infected_male,
            'd_male' => $data_deaths_male,
            'i_female' => $data_infected_female,
            'd_female' => $data_deaths_female,
        );

        // return $district_list;

        return view('district.show',[
            'year' => $this_year,
            'state' => $this_state,
            'district_list' => $district_list,
            'district' => $district,
            'single_district' => $this_district,
            'datalist' => $datalist,
            'data_district_year' => $data_district_year,
        ]);
    }
}