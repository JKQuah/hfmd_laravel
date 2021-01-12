<?php

namespace App\Http\Controllers;

use App\Data;
use Illuminate\Support\Carbon;
use App\Http\Controllers\State\StateChartTraits;
use App\Http\Controllers\State\StateDataTraits;

class DataController extends Controller
{
    use StateChartTraits;
    use StateDataTraits;

    public function index()
    {
        $data_analysed = $this->getStateYearlyData();
        return view('data.index', compact('data_analysed'));
    }

    public function show($year, $state)
    {
        if(!$this->verifyState($state)){
            return redirect()->back()->with("missing", "State doesn\'t not exist in the database");
        }

        if(!$this->verifyYear($year)){
            return redirect()->back()->with("missing", "Year ".$year." doesn\'t not exist in the database.");
        }

        $data_weekly_count = $this->getStateWeeklyData($year, $state);
        $analysed = $this->getStateMonthlyData($year, $state);
        $all_weeks = $this->getAllWeeks();
        $all_months = $this->getAllMonths();

        $data_monthly_count = $analysed['data_monthly_count'];
        $district_analysis = $analysed['district_analysis'];      

        $total_infected_per_year = $analysed['total_infected_per_year'];
        $total_death_per_year  = $analysed['total_death_per_year'];
        $total_male_per_year = $analysed['total_male_per_year'];
        $total_female_per_year = $analysed['total_female_per_year'];

        // Declare categories
        $category_A = $analysed['category_A'];
        $range_A = 100;
        $category_B = $analysed['category_B'];
        $range_B = 200;
        $category_C = $analysed['category_C'];
        $range_C = 300;
        $category_D = $analysed['category_D'];

        // get analysed result
        $analysed_result = [
            'highest' => $this->getStatisticsResult($district_analysis, 'max'),
            'lowest' => $this->getStatisticsResult($district_analysis, 'min'),
            'average' => $this->getStatisticsResult($district_analysis, 'avg'),
        ];


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

        return view('data.show', [
            'this_state' => $state,
            'this_year' => $year,
            'states' => $this->getAllStates(),
            'years' => $this->getAllYears(),
            'months' => $all_months,
            'weeks' => $all_weeks,
            'total_infected' => $total_infected_per_year,
            'total_deaths' => $total_death_per_year,
            'total_male' => $total_male_per_year,
            'total_female' => $total_female_per_year,
            'monthly_details' => $data_monthly_count,
            'monthly_analysis' => $district_analysis,
            'analysed_result' => $analysed_result,
            'weekly_details' => $data_weekly_count,
            'category_data' => $category_data,
        ]);
    }

    

    


}
