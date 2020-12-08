<?php

namespace App\Http\Controllers\State;

use App\Data;
use Illuminate\Support\Carbon;

/**
 * Retrieve required data for state according to year and state
 */
trait StateDataTraits
{
    public function getStateMonthlyData($year, $state)
    {
        // get monthly data using groupby
        $monthly_data = Data::where('state', $state)->whereYear('notificationDate', $year)->get()->groupBy([
            'district',
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('M');
            }
        ], $preserveKeys = true);

        $all_months = $this->getAllMonths();
        // declare variables
        $district_analysis = [];
        $total_case_per_year = 0;
        $total_infected_per_year = 0;
        $total_death_per_year  = 0;
        $total_male_per_year = 0;
        $total_female_per_year = 0;

        // Declare categories
        $category_A = [];
        $range_A = 100;
        $category_B = [];
        $range_B = 200;
        $category_C = [];
        $range_C = 300;
        $category_D = [];

        foreach ($monthly_data as $district => $months) {
            $total_case_per_district = 0;
            $total_infected_per_district = 0;
            $total_death_per_district = 0;
            $total_male_per_district = 0;
            $total_female_per_district = 0;

            foreach ($months as $month => $cases) {
                $data_monthly_count[$district][$month] = count($cases);
                $total_case_per_district += count($cases);
                $total_infected_per_district += count($cases->where('status', 'infected'));
                $total_death_per_district += count($cases->where('status', 'death'));
                $total_male_per_district += count($cases->where('gender', 'Male'));
                $total_female_per_district += count($cases->where('gender', 'Female'));
            }
            foreach ($all_months as $months => $month) {
                if (!isset($data_monthly_count[$district][$month])) {
                    $data_monthly_count[$district][$month] = 0;
                }
            }
            // sort the data monthly count array according to month
            uksort($data_monthly_count[$district], function ($month1, $month2) {
                $time1 = strtotime($month1);
                $time2 = strtotime($month2);
                return $time1 - $time2;
            });

            $district_analysis[$district]['total'] = $total_case_per_district;
            $district_analysis[$district]['infected'] = $total_infected_per_district;
            $district_analysis[$district]['death'] = $total_death_per_district;
            $district_analysis[$district]['male'] = $total_male_per_district;
            $district_analysis[$district]['female'] = $total_female_per_district;

            // categories district
            if ($total_case_per_district < $range_A) {
                $category_A += [$district => $total_case_per_district];
            } else if ($total_case_per_district < $range_B) {
                $category_B += [$district => $total_case_per_district];
            } else if ($total_case_per_district < $range_C) {
                $category_C += [$district => $total_case_per_district];
            } else {
                $category_D += [$district => $total_case_per_district];
            }

            // Accumulate the data from state into year
            $total_infected_per_year += $total_infected_per_district;
            $total_death_per_year += $total_death_per_district;
            $total_male_per_year += $total_male_per_district;
            $total_female_per_year += $total_female_per_district;
        }

        return [
            'data_monthly_count' => $data_monthly_count,
            'district_analysis' => $district_analysis, 
            'category_A' => $category_A,
            'category_B' => $category_B,
            'category_C' => $category_C,
            'category_D' => $category_D, 
            'total_infected_per_year' => $total_infected_per_year,
            'total_death_per_year' => $total_death_per_year,
            'total_male_per_year' => $total_male_per_year,
            'total_female_per_year' => $total_female_per_year,
        ];
    }

    public function getStateWeeklyData($year, $state){
        // get weekly data using groupby
        $weekly_data = Data::where('state', $state)->whereYear('notificationDate', $year)->get()->groupBy([
            'district',
            function ($data) {
                return Carbon::parse($data->notificationDate)->format('W');
            }
        ], $preserveKeys = true);

        $all_weeks = $this->getAllWeeks();

        // count weekly data
        foreach ($weekly_data as $district => $weeks) {
            foreach ($weeks as $week => $value) {
                $data_weekly_count[$district][intval($week)] = count($value);
            }
            foreach ($all_weeks as $week) {
                if (!isset($data_weekly_count[$district][$week])) {
                    $data_weekly_count[$district][$week] = 0;
                }
            }
            ksort($data_weekly_count[$district]);
        }
        return $data_weekly_count;
    }
}
