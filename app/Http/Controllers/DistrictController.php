<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\District\DistrictDataTraits;
use App\Http\Controllers\District\DistrictChartTraits;

class DistrictController extends Controller
{
    use DistrictDataTraits;
    use DistrictChartTraits;

    public function index($this_year, $this_state, $district){
        if(!$this->verifyState($this_state)){
            return redirect()->back()->with("missing", "State doesn\'t not exist");
        }

        if(!$this->verifyDistrict($this_state, $district)){
            return redirect()->back()->with("missing", "District doesn\'t not exist");
        }

        if(!$this->verifyYear($this_year)){
            return redirect()->back()->with("missing", "Year ".$this_year." doesn\'t not exist in the database.");
        }
        $years = Data::select(DB::raw('YEAR(notificationDate) as year'))->distinct()->orderBy('year')->get();
        
        $district_list = Data::select('district')
                        ->where('state', $this_state)
                        ->groupBy('district')
                        ->get();

        $datalist = $this->getDistrictData($district);
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
                            
        $this_district_details = array(
            'infected' => $data_infected,
            'deaths' => $data_deaths,
            'i_male' => $data_infected_male,
            'd_male' => $data_deaths_male,
            'i_female' => $data_infected_female,
            'd_female' => $data_deaths_female,
        );

        // dd($datalist);

        return view('district.index',[
            'year' => $this_year,
            'state' => $this_state,
            'district_list' => $district_list,
            'district' => $district,
            'single_district' => $this_district_details,
            'datalist' => $datalist,
            'data_district_year' => $data_district_year,
        ]);
    }
}